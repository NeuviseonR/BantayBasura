<?php

namespace Database\Seeders;

use App\Models\Barangay;
use Illuminate\Database\Seeder;

class BarangaySeeder extends Seeder
{
    /**
     * Edit this list so it matches your real barangays.
     * It can be run again safely: existing names are skipped.
     */
    public function run(): void
    {
        $names = [
            'Cabadiangan', 'Calajoan', 'Camp 7', 'Cuanos', 'Guindaruhan',
            'Linao', 'Lipata', 'Manduang', 'Pakigne',
            'Poblacion Ward I', 'Poblacion Ward II', 'Poblacion Ward III', 'Poblacion Ward IV',
            'Tubod', 'Tulay', 'Tunghaan', 'Tungkil', 'Tungkop', 'Vito',
        ];

        foreach ($names as $name) {
            Barangay::firstOrCreate(['name' => $name]);
        }
    }
}
