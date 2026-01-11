<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\User;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MedicineController extends Controller
{
    /**
     * Display the Admin Dashboard with Reports, Statistics, and Monthly Details
     */
    public function adminDashboard(Request $request)
{
    $this->captureExpiredMedicines();

    $totalMedicines = Medicine::count();
    
    // CHANGE THIS: Count all instead of filtering for 'pending'
    $totalAppointments = Appointment::count(); 
    
    $totalPatients = User::where('role', 'user')->count();

        // 3. Monthly Trends (Last 6 Months) for Static Line Charts
        $months = [];
        $releasesData = [];
        $expirationsData = [];

        for ($i = 5; $i >= 0; $i--) {
            $monthDate = Carbon::now()->subMonths($i);
            $months[] = $monthDate->format('M Y'); 

            $releasesData[] = MedicineHistory::where('action_type', 'Released')
                ->whereMonth('performed_at', $monthDate->month)
                ->whereYear('performed_at', $monthDate->year)
                ->sum(DB::raw('ABS(quantity_changed)')); 

            $expirationsData[] = MedicineHistory::where('action_type', 'Expired')
                ->whereMonth('performed_at', $monthDate->month)
                ->whereYear('performed_at', $monthDate->year)
                ->count();
        }

        // 4. Alerts (Real-time data for tables)
        $lowStock = Medicine::where('stock_quantity', '<', 10)->get();
        $expiringSoon = Medicine::where('expiry_date', '<=', Carbon::now()->addDays(30))->get();

        // 5. Monthly Details for the "Historical Peek" list (Dropdown)
        // Uses filters from the request or defaults to the current month
        $selectedMonth = $request->get('month', Carbon::now()->month);
        $selectedYear = $request->get('year', Carbon::now()->year);

        $monthlyDetails = MedicineHistory::whereMonth('performed_at', $selectedMonth)
            ->whereYear('performed_at', $selectedYear)
            ->whereIn('action_type', ['Released', 'Expired'])
            ->orderBy('performed_at', 'desc')
            ->get();

return view('admin.dashboard', compact(
        'totalMedicines', 
        'totalAppointments', 
        'totalPatients', 
        'lowStock', 
        'expiringSoon',
        'monthlyDetails',
        'months',          // <--- ADD THIS LINE
        'releasesData',    // <--- Ensure these are also included
        'expirationsData'  // <--- Ensure these are also included
    ));
}

    /**
     * AJAX Endpoint: Get Report Data & Log details for specific Month/Year
     */
    public function getMonthlyReport(Request $request)
    {
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);

        // Aggregate Totals for the dynamic counters/doughnut chart
        $releasesCount = MedicineHistory::where('action_type', 'Released')
            ->whereMonth('performed_at', $month)
            ->whereYear('performed_at', $year)
            ->sum(DB::raw('ABS(quantity_changed)'));

        $expirationsCount = MedicineHistory::where('action_type', 'Expired')
            ->whereMonth('performed_at', $month)
            ->whereYear('performed_at', $year)
            ->count();

        // Detailed History Rows to refresh the "View Details" list via Javascript
        $details = MedicineHistory::whereMonth('performed_at', $month)
            ->whereYear('performed_at', $year)
            ->whereIn('action_type', ['Released', 'Expired'])
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
            'releases' => $releasesCount,
            'expirations' => $expirationsCount,
            'details' => $details,
            'formatted_date' => Carbon::createFromDate($year, $month, 1)->format('F Y')
        ]);
    }

    /**
     * CRUD: Inventory List (Index)
     */
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

        return view('admin.medicines.index', compact('medicines', 'patients'));
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
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required', 
        ]);

        $expiryDate = $request->expiry_date . '-01';

        $medicine = Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'expiry_date' => $expiryDate,
        ]);

        $this->logHistory($medicine->name, 'Added', $medicine->stock_quantity, 'Initial stock added.');

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
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required',
        ]);

        $medicine = Medicine::findOrFail($id);
        $qtyDiff = $request->stock_quantity - $medicine->stock_quantity;
        $expiryDate = $request->expiry_date . '-01';

        $medicine->update([
            'name' => $request->name,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'expiry_date' => $expiryDate,
        ]);

        $this->logHistory($medicine->name, 'Edited', $qtyDiff, 'Medicine details updated.');

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine updated successfully!');
    }

    public function release(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $medicine = Medicine::findOrFail($id);
        
        if ($medicine->stock_quantity < $request->quantity) {
            return back()->withErrors(['quantity' => 'Insufficient stock available.']);
        }

        $patient = User::find($request->patient_id);
        $medicine->decrement('stock_quantity', $request->quantity);

        $this->logHistory(
            $medicine->name, 
            'Released', 
            -$request->quantity, 
            "Released {$request->quantity} items to Patient: {$patient->first_name} {$patient->last_name}"
        );

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine released successfully!');
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        $this->logHistory($medicine->name, 'Deleted', -$medicine->stock_quantity, 'Medicine removed from inventory.');
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully!');
    }

    public function history()
    {
        $history = MedicineHistory::orderBy('performed_at', 'desc')->get();
        return view('admin.medicines.history', compact('history'));
    }

    /**
     * Logic: Identify and log expired medicines
     */
    private function captureExpiredMedicines()
    {
        $today = Carbon::today()->format('Y-m-d');
        $medicines = Medicine::where('expiry_date', '<=', $today)->get();

        foreach ($medicines as $medicine) {
            $alreadyLogged = MedicineHistory::where('medicine_name', $medicine->name)
                ->where('action_type', 'Expired')
                ->whereMonth('performed_at', Carbon::now()->month)
                ->whereYear('performed_at', Carbon::now()->year)
                ->exists();

            if (!$alreadyLogged && $medicine->stock_quantity > 0) {
                $this->logHistory(
                    $medicine->name, 
                    'Expired', 
                    -$medicine->stock_quantity, 
                    "Medicine expired on " . $medicine->expiry_date
                );
                // Zero out stock for expired items
                $medicine->update(['stock_quantity' => 0]);
            }
        }
    }

    /**
     * Helper: Log History to DB
     */
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
}