<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;

class MedicineController extends Controller
{
    // Display list of medicines
    public function index()
    {
        $medicines = Medicine::all(); // Fetch all medicines
        return view('admin.medicines.index', compact('medicines'));
    }

    // Show the form to add a new medicine
    public function create()
    {
        return view('admin.medicines.create');
    }

    // Store a new medicine in the database
    public function store(Request $request)
    {
        // 1. Validate Input
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today', // Must be in the future
        ]);

        // 2. Create Data
        Medicine::create($request->all());

        // 3. Redirect with Success Message
        return redirect()->route('admin.medicines.index')->with('success', 'Medicine added successfully!');
    }
    
    // Delete a medicine
    public function destroy($id)
    {
        Medicine::destroy($id);
        return redirect()->route('admin.medicines.index')->with('success', 'Medicine deleted successfully!');
    }
}