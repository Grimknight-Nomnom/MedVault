<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\MedicineHistory;
use App\Models\User;
use Carbon\Carbon;

class MedicineController extends Controller
{
    // Display list (Index)
    public function index(Request $request)
    {
        $query = Medicine::query();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('category', 'like', "%{$searchTerm}%");
        }

        $medicines = $query->get();
        
        // Fetch Patients for the Release Modal
        $patients = User::where('role', 'user')->orderBy('first_name')->get();

        return view('admin.medicines.index', compact('medicines', 'patients'));
    }

    // Show Create Form
    public function create()
    {
        return view('admin.medicines.create');
    }

    // Store New Medicine
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required', // Format YYYY-MM coming from type="month"
        ]);

        // Append '-01' to satisfy SQL Date format (YYYY-MM-01)
        $expiryDate = $request->expiry_date . '-01';

        $medicine = Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
            'stock_quantity' => $request->stock_quantity,
            'expiry_date' => $expiryDate,
        ]);

        // Log History
        $this->logHistory($medicine->name, 'Added', $medicine->stock_quantity, 'Initial stock added.');

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine added successfully!');
    }

    // Show Edit Form
    public function edit($id)
    {
        $medicine = Medicine::findOrFail($id);
        return view('admin.medicines.edit', compact('medicine'));
    }

    // Update Medicine
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'expiry_date' => 'required',
        ]);

        $medicine = Medicine::findOrFail($id);
        
        // Calculate diff for history log
        $qtyDiff = $request->stock_quantity - $medicine->stock_quantity;
        
        // Append '-01'
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

    // Release Medicine to Patient
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
        
        // Deduct Stock
        $medicine->decrement('stock_quantity', $request->quantity);

        // Log History
        $this->logHistory(
            $medicine->name, 
            'Released', 
            -$request->quantity, 
            "Released {$request->quantity} items to Patient: {$patient->first_name} {$patient->last_name}"
        );

        return redirect()->route('admin.medicines.index')->with('success', 'Medicine released successfully!');
    }

    // Delete Medicine
    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        
        // Log before delete so we keep the name
        $this->logHistory($medicine->name, 'Deleted', -$medicine->stock_quantity, 'Medicine removed from inventory.');

        $medicine->delete();
        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully!');
    }

    // View History
    public function history()
    {
        $history = MedicineHistory::orderBy('performed_at', 'desc')->get();
        return view('admin.medicines.history', compact('history'));
    }

    // Helper: Log History
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