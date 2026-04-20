<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Staff;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class MedicineController extends Controller
{
    /**
     * Display the Admin Dashboard
     */
    public function adminDashboard(Request $request)
    {
        // Check for expired medicines on dashboard load
        $this->captureExpiredMedicines();

        // 1. Daily Appointments Count
        $todayAppointmentsCount = Appointment::whereDate('appointment_date', Carbon::today())->count();

        // 2. Counts
        $totalMedicines = Medicine::count();
        $totalAppointments = Appointment::count();
        $totalPatients = User::where('role', 'user')->count();

        // 3. Alerts
        $lowStock = Medicine::where('stock_quantity', '<', 10)->get();
        $expiringSoon = Medicine::where('expiry_date', '<=', now()->addDays(30))->get();

        // 4. Initial Monthly Details
        $monthlyDetails = MedicineHistory::whereMonth('performed_at', now()->month)
            ->whereYear('performed_at', now()->year)
            ->whereIn('action_type', ['Released', 'Expired'])
            ->get();

        return view('admin.dashboard', compact(
            'todayAppointmentsCount',
            'totalMedicines',
            'totalAppointments',
            'totalPatients',
            'lowStock',
            'expiringSoon',
            'monthlyDetails'
        ));
    }

    /**
     * API: Get Inventory Trends Data (Month vs Week)
     */
    public function getTrendsData(Request $request)
    {
        $filter = $request->get('filter', 'month');
        $labels = [];
        $releases = [];
        $expirations = [];

        if ($filter === 'week') {
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subWeeks($i);
                $start = $date->copy()->startOfWeek();
                $end = $date->copy()->endOfWeek();
                
                $labels[] = 'W' . $date->week . ' (' . $start->format('M d') . ')';

                $releases[] = MedicineHistory::where('action_type', 'Released')
                    ->whereBetween('performed_at', [$start, $end])
                    ->sum(DB::raw('ABS(quantity_changed)'));

                $expirations[] = MedicineHistory::where('action_type', 'Expired')
                    ->whereBetween('performed_at', [$start, $end])
                    ->sum(DB::raw('ABS(quantity_changed)'));
            }
        } else {
            for ($i = 5; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $labels[] = $date->format('M Y');

                $releases[] = MedicineHistory::where('action_type', 'Released')
                    ->whereMonth('performed_at', $date->month)
                    ->whereYear('performed_at', $date->year)
                    ->sum(DB::raw('ABS(quantity_changed)'));

                $expirations[] = MedicineHistory::where('action_type', 'Expired')
                    ->whereMonth('performed_at', $date->month)
                    ->whereYear('performed_at', $date->year)
                    ->sum(DB::raw('ABS(quantity_changed)'));
            }
        }

        return response()->json([
            'labels' => $labels,
            'releases' => $releases,
            'expirations' => $expirations
        ]);
    }

    /**
     * API: Get Historical Peek Data (Month vs Week)
     */
    public function getPeekData(Request $request)
    {
        $mode = $request->get('mode', 'month');
        $query = MedicineHistory::query();
        $formattedDate = '';

        if ($mode === 'week') {
            $weekStr = $request->get('week', Carbon::now()->format('Y-\WW'));
            if (!$weekStr) $weekStr = Carbon::now()->format('Y-\WW');

            $year = (int)substr($weekStr, 0, 4);
            $week = (int)substr($weekStr, 6);
            
            $start = Carbon::now()->setISODate($year, $week)->startOfWeek();
            $end = Carbon::now()->setISODate($year, $week)->endOfWeek();

            $query->whereBetween('performed_at', [$start->startOfDay(), $end->endOfDay()]);
            $formattedDate = "Week $week (" . $start->format('M d') . ' - ' . $end->format('M d') . ')';
        } else {
            $month = $request->get('month', Carbon::now()->month);
            $year = $request->get('year', Carbon::now()->year);
            
            $query->whereMonth('performed_at', $month)->whereYear('performed_at', $year);
            $formattedDate = Carbon::createFromDate($year, $month, 1)->format('F Y');
        }

        $releasesCount = (clone $query)->where('action_type', 'Released')->sum(DB::raw('ABS(quantity_changed)'));
        $expirationsCount = (clone $query)->where('action_type', 'Expired')->sum(DB::raw('ABS(quantity_changed)'));

        $details = $query->whereIn('action_type', ['Released', 'Expired'])
            ->orderBy('performed_at', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'name' => $item->medicine_name,
                    'action' => $item->action_type,
                    'qty' => abs($item->quantity_changed),
                    'date' => Carbon::parse($item->performed_at)->format('M d, Y'),
                    'desc' => $item->description
                ];
            });

        return response()->json([
            'releases' => (int)$releasesCount,
            'expirations' => (int)$expirationsCount,
            'details' => $details,
            'formatted_date' => $formattedDate
        ]);
    }

public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
        }

        $medicines = $query->get();
        $patients = User::where('role', 'user')->orderBy('first_name')->get();
        
        // OLD: $staffList = Staff::orderBy('name')->get(); 
        // NEW: Change the $staffList line to this:
        $staffList = Staff::whereIn('role', ['Doctor', 'Nurse', 'doctor', 'nurse', 'DOCTOR', 'NURSE'])
                          ->orderBy('name')
                          ->get(); 

        return view('admin.medicines.index', compact('medicines', 'patients', 'staffList'));
    }

    public function create()
    {
        return view('admin.medicines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required', 
        ]);

        // Format to first day of month (e.g., 2026-04-01)
        $expiryDate = $request->expiry_date . '-01';

        $medicine = Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'stock_quantity' => $request->stock_quantity,
            'expiry_date' => $expiryDate,
        ]);

        $this->logHistory($medicine->name, 'Added', $medicine->stock_quantity, 'Initial stock added.');

        // IMMEDIATE CHECK: Check if the newly added medicine is already expired
        $this->checkSingleExpiration($medicine);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine added successfully!');
    }

    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'description' => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required',
        ]);

        $medicine = Medicine::findOrFail($id);
        $qtyDiff = $request->stock_quantity - $medicine->stock_quantity;
        $expiryDate = $request->expiry_date . '-01';

        $medicine->update([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'stock_quantity' => $request->stock_quantity,
            'expiry_date' => $expiryDate,
        ]);

        if ($qtyDiff != 0) {
            $this->logHistory($medicine->name, 'Edited', $qtyDiff, 'Medicine details updated.');
        }

        // IMMEDIATE CHECK: Check if the updated medicine is now expired
        $this->checkSingleExpiration($medicine);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine updated successfully!');
    }

    public function release(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
            'released_by' => 'required|string', 
        ]);

        $medicine = Medicine::findOrFail($id);
        
        if ($medicine->stock_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        $patient = User::find($request->patient_id);
        $medicine->decrement('stock_quantity', $request->quantity);

        // 1. Log in Admin History
        $this->logHistory(
            $medicine->name, 
            'Released', 
            -$request->quantity, 
            "Released {$request->quantity} items to Patient: {$patient->first_name} {$patient->last_name} (By: {$request->released_by})"
        );

        // 2. Create Medical Record for the Patient
        MedicalRecord::create([
            'user_id' => $patient->id,
            'appointment_id' => null, 
            'diagnosis' => 'MEDICINE_DISPENSED',
            'prescription' => $medicine->name,
            'notes' => "{$request->quantity}|{$medicine->description}|{$request->released_by}", 
        ]);

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine released successfully!');
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $this->logHistory($medicine->name, 'Deleted', -$medicine->stock_quantity, 'Medicine removed from inventory.');
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully!');
    }

    public function history(Request $request)
    {
        $query = MedicineHistory::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function($q) use ($search) {
                $q->where('medicine_name', 'like', "%{$search}%")
                  ->orWhere('action_type', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");

                try {
                    $date = \Carbon\Carbon::parse($search);
                    if (preg_match('/^[a-zA-Z]+$/', $search)) {
                        $q->orWhereMonth('performed_at', $date->month);
                    } elseif (preg_match('/^\d{4}$/', $search)) {
                        $q->orWhereYear('performed_at', $search);
                    } else {
                        $q->orWhereDate('performed_at', $date->format('Y-m-d'));
                    }
                } catch (\Exception $e) {}
            });
        }

        $history = $query->orderBy('performed_at', 'desc')
                        ->get()
                        ->map(function($item) {
                            // Format date with time
                            $item->formatted_date = \Carbon\Carbon::parse($item->performed_at)->format('F d, Y');
                            $item->formatted_time = \Carbon\Carbon::parse($item->performed_at)->format('h:i A');
                            
                            // Format description based on action type
                            if ($item->action_type === 'Added') {
                                $item->display_description = 'Initial stock added.';
                            } elseif ($item->action_type === 'Deleted') {
                                $item->display_description = 'Medicine removed from inventory.';
                            } elseif ($item->action_type === 'Edited') {
                                $item->display_description = 'Medicine details updated.';
                            } elseif ($item->action_type === 'Released') {
                                $item->display_description = $item->description; // e.g., "Released 5 items to Patient: John Doe (By: Dr. Smith)"
                            } elseif ($item->action_type === 'Expired') {
                                if ($item->action_taken) {
                                    $actionLabel = ucfirst($item->action_taken);
                                    $item->display_description = "Medicine expired on " . \Carbon\Carbon::parse($item->performed_at)->format('F Y') . " - {$actionLabel}";
                                    $item->status = "Expired - {$actionLabel}";
                                } else {
                                    $item->display_description = "Medicine expired on " . \Carbon\Carbon::parse($item->performed_at)->format('F Y');
                                    $item->status = "Expired";
                                }
                            } else {
                                $item->display_description = $item->description;
                                $item->status = $item->action_type;
                            }
                            
                            // Set status if not already set
                            if (!isset($item->status)) {
                                $item->status = $item->action_type;
                            }
                            
                            return $item;
                        });

        return view('admin.medicines.history', compact('history'));
    }

    public function patientIndex(Request $request)
    {
        $query = Medicine::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $medicines = $query->orderBy('name')->paginate(10);
        return view('patient.medicines.index', compact('medicines'));
    }

    /**
     * Export Expired and Out of Stock Medicines to Excel (.xls)
     */
    public function exportExpired()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Fetch medicines that have 0 stock OR are expired
        $medicines = Medicine::where('stock_quantity', '<=', 0)
                             ->orWhere('expiry_date', '<=', $today)
                             ->get();

        // Change extension to .xls so Excel renders the HTML formatting
        $fileName = 'Expired_And_Empty_Stock_Meds_' . date('Y-m-d') . '.xls';

        $headers = array(
            "Content-type"        => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $callback = function() use($medicines, $today) {
            // Echo HTML table structure (Excel reads this natively)
            echo '<html><body><table border="1">';
            
            // ROW 1: Headers (All Bold)
            echo '<tr>';
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">#</th>'; // A1 is the number header
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">Name</th>';
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">Category</th>';
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">Description</th>';
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">Stock</th>';
            echo '<th style="font-weight:bold; background-color:#f8f9fa;">Expiry Date</th>';
            echo '</tr>';

            $counter = 1; // Start the counter for Column A

            // ROWS 2+: Data
            foreach ($medicines as $medicine) {
                // Check if they are expired or out of stock to apply styling
                $isZeroStock = $medicine->stock_quantity <= 0;
                $isExpired = $medicine->expiry_date <= $today;

                echo '<tr>';
                
                // Column A: Number sequence (1, 2, 3...) - NOW BOLD
                echo '<td style="font-weight: bold;">' . $counter++ . '</td>'; 
                
                echo '<td>' . htmlspecialchars($medicine->name) . '</td>';
                echo '<td>' . htmlspecialchars($medicine->category) . '</td>';
                
                // Truncate Description to 50 characters maximum
                $shortDescription = Str::limit($medicine->description, 50, '...');
                echo '<td>' . htmlspecialchars($shortDescription) . '</td>';
                
                // Highlight Stock Cell RED if 0
                if ($isZeroStock) {
                    echo '<td style="background-color: #ffcccc; color: #cc0000; font-weight: bold;">' . $medicine->stock_quantity . '</td>';
                } else {
                    echo '<td>' . $medicine->stock_quantity . '</td>';
                }

                // Format Date to Month and Year only (e.g. "January 2026")
                $formattedExpiry = \Carbon\Carbon::parse($medicine->expiry_date)->format('F Y');

                // Highlight Expiry Cell RED if expired
                if ($isExpired) {
                    echo '<td style="background-color: #ffcccc; color: #cc0000; font-weight: bold;">' . $formattedExpiry . '</td>';
                } else {
                    echo '<td>' . $formattedExpiry . '</td>';
                }
                
                echo '</tr>';
            }

            echo '</table></body></html>';
        };

        return response()->stream($callback, 200, $headers);
    }

    // --- HELPER FUNCTIONS ---

    /**
     * Checks if a single medicine is expired and has stock.
     * If so, logs it and sets stock to 0.
     */
    private function checkSingleExpiration($medicine)
    {
        $today = Carbon::today()->format('Y-m-d');

        if ($medicine->expiry_date <= $today && $medicine->stock_quantity > 0) {
            $formattedExpiry = Carbon::parse($medicine->expiry_date)->format('F Y');

            $this->logHistory(
                $medicine->name, 
                'Expired', 
                -$medicine->stock_quantity, 
                "Medicine expired on " . $formattedExpiry
            );

            $medicine->update(['stock_quantity' => 0]);
        }
    }

    /**
     * Bulk check for dashboard. Safely checks all medicines.
     */
    private function captureExpiredMedicines()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $medicines = Medicine::where('expiry_date', '<=', $today)
                             ->where('stock_quantity', '>', 0)
                             ->get();

        foreach ($medicines as $medicine) {
            $alreadyLogged = MedicineHistory::where('medicine_name', $medicine->name)
                ->where('action_type', 'Expired')
                ->whereDate('performed_at', Carbon::today())
                ->exists();

            if (!$alreadyLogged) {
                $this->checkSingleExpiration($medicine);
            }
        }
    }

    private function logHistory($name, $action, $qty, $desc)
    {
        MedicineHistory::create([
            'medicine_name' => $name,
            'action_type' => $action,
            'quantity_changed' => $qty,
            'description' => $desc,
            'performed_at' => now(),
        ]);
    }

    /**
     * Display all expired medicines that need action
     */
    public function expiredMedicines()
    {
        $expiredRecords = MedicineHistory::where('action_type', 'Expired')
            ->orderBy('performed_at', 'desc')
            ->get()
            ->map(function($record) {
                // Format the date to show only Month and Year
                $record->formatted_date = \Carbon\Carbon::parse($record->performed_at)->format('F Y');
                return $record;
            });

        return view('admin.medicines.expired', compact('expiredRecords'));
    }

    /**
     * Record action taken on an expired medicine
     */
    public function recordAction(Request $request, $id)
    {
        $request->validate([
            'action_taken' => 'required|in:disposed,returned,donated,destroyed',
            'action_notes' => 'nullable|string|max:500',
        ]);

        $history = MedicineHistory::findOrFail($id);

        $history->update([
            'action_taken' => $request->action_taken,
            'action_notes' => $request->action_notes,
        ]);

        // After recording action, redirect to inventory history
        $actionLabel = ucfirst($request->action_taken);
        return redirect()->route('admin.medicines.history')
                        ->with('success', "Medicine marked as {$actionLabel}. View history below.");
    }
}