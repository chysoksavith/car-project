<?php

namespace Database\Seeders;

use App\Models\Fuel;
use Illuminate\Database\Seeder;

class FuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fuels = [
            [
                'name' => 'Petrol',
                'code' => 'PETROL',
                'description' => 'Internal combustion engine powered by gasoline.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Diesel',
                'code' => 'DIESEL',
                'description' => 'Internal combustion engine powered by diesel fuel.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Hybrid',
                'code' => 'HYBRID',
                'description' => 'Combines a fuel engine with an electric motor.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Plug-in Hybrid (PHEV)',
                'code' => 'PHEV',
                'description' => 'Hybrid vehicle that can be charged externally.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Electric (EV)',
                'code' => 'EV',
                'description' => 'Fully electric vehicle powered by batteries.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Hydrogen',
                'code' => 'HYDROGEN',
                'description' => 'Vehicle powered by hydrogen fuel cells.',
                'sort_order' => 6,
            ],
            [
                'name' => 'LPG',
                'code' => 'LPG',
                'description' => 'Liquefied Petroleum Gas powered vehicle.',
                'sort_order' => 7,
            ],
            [
                'name' => 'CNG',
                'code' => 'CNG',
                'description' => 'Compressed Natural Gas powered vehicle.',
                'sort_order' => 8,
            ],
        ];

        $company = \App\Models\Company::first();
        if (!$company) {
            $company = \App\Models\Company::create(['name' => 'Default Company']);
        }

        foreach ($fuels as $fuel) {
            Fuel::withoutGlobalScopes()->updateOrCreate(
                ['code' => $fuel['code'], 'company_id' => $company->id],
                $fuel
            );
        }
    }
}
