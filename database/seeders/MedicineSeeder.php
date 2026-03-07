<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 5 standard clinic medicines with stock between 100 and 150
        $medicines = Medicine::all();
        
        if ($medicines->isEmpty()) {
            Medicine::insert([
                [
                    'name' => 'Paracetamol 500mg', 
                    'brand' => 'Biogesic',
                    'category' => 'tablet',
                    'description' => 'For fever and mild pain',
                    'stock_quantity' => rand(100, 150), 
                    'expiry_date' => '2027-01-01', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'name' => 'Amoxicillin 250mg', 
                    'brand' => 'Amoxil',
                    'category' => 'capsule',
                    'description' => 'For bacterial infections',
                    'stock_quantity' => rand(100, 150), 
                    'expiry_date' => '2026-12-01', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'name' => 'Ascorbic Acid (Vitamin C)', 
                    'brand' => 'Ceelin',
                    'category' => 'syrup',
                    'description' => 'Immunity booster',
                    'stock_quantity' => rand(100, 150), 
                    'expiry_date' => '2028-05-01', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'name' => 'Ibuprofen 400mg', 
                    'brand' => 'Advil',
                    'category' => 'pills',
                    'description' => 'Anti-inflammatory and pain relief',
                    'stock_quantity' => rand(100, 150), 
                    'expiry_date' => '2027-08-15', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
                [
                    'name' => 'Loratadine 10mg', 
                    'brand' => 'Claritin',
                    'category' => 'drops',
                    'description' => 'For allergies',
                    'stock_quantity' => rand(100, 150), 
                    'expiry_date' => '2027-11-20', 
                    'created_at' => now(), 
                    'updated_at' => now()
                ],
            ]);
            $medicines = Medicine::all();
        }

        // 2. Generate exact amounts of histories between Dec 2025 and Feb 2026
        $medData = $medicines->keyBy('name')->toArray();
        $medicineNames = array_keys($medData);
        $histories = [];
        
        // Define exact limits requested
        $totalReleased = rand(50, 100); 
        $totalExpired = rand(20, 50);
        $totalAdded = rand(10, 20); // A few restocks just to balance things out

        // Build a flat array of all the actions we need to generate
        $actionsToGenerate = array_merge(
            array_fill(0, $totalReleased, 'Released'),
            array_fill(0, $totalExpired, 'Expired'),
            array_fill(0, $totalAdded, 'Added')
        );

        // Date boundaries (Dec 1, 2025 to Feb 28, 2026)
        $startTimestamp = Carbon::create(2025, 12, 1)->timestamp;
        $endTimestamp = Carbon::create(2026, 2, 28, 23, 59, 59)->timestamp;

        foreach ($actionsToGenerate as $action) {
            $randomMedName = $medicineNames[array_rand($medicineNames)];
            $medExpiry = Carbon::parse($medData[$randomMedName]['expiry_date'])->format('M d, Y');
            
            // Pick a random date within our 3-month window
            $randomDate = Carbon::createFromTimestamp(rand($startTimestamp, $endTimestamp));

            if ($action === 'Released') {
                $quantity = rand(1, 4); // Keep quantities low so we don't zero out the 100 stock
                $quantityChanged = -$quantity;
                $description = "Simulated historical release. Expiry tracking: $medExpiry";
            } elseif ($action === 'Expired') {
                $quantity = rand(1, 2);
                $quantityChanged = -$quantity;
                $description = "Simulated historical expiration. Disposed of expired stock. Original Expiry: $medExpiry";
            } else {
                $quantity = rand(10, 20);
                $quantityChanged = $quantity;
                $description = "Simulated historical restock. Expiry tracking: $medExpiry";
            }

            $histories[] = [
                'medicine_name' => $randomMedName,
                'action_type' => $action,
                'quantity_changed' => $quantityChanged, 
                'description' => $description,
                'performed_at' => $randomDate->format('Y-m-d H:i:s'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Sort the generated logs chronologically so they look correct in the database
        usort($histories, function($a, $b) {
            return strtotime($a['performed_at']) <=> strtotime($b['performed_at']);
        });

        // Insert in chunks to prevent memory limits
        foreach (array_chunk($histories, 500) as $chunk) {
            DB::table('medicine_histories')->insert($chunk);
        }
    }
}