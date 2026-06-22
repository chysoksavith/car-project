<?php

namespace Database\Seeders;

use App\Models\CarModel;
use App\Models\Color;
use App\Models\Company;
use App\Models\Fuel;
use App\Models\Maker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Company A
        $company = Company::withoutGlobalScopes()->updateOrCreate(
            ['name' => 'Company A'],
            [
                'email' => 'contact@companya.com',
                'phone' => '+1234567890',
                'address' => '123 Company A Street',
                'is_active' => true,
            ]
        );

        $this->command->info("Created Company: {$company->name}");

        // 2. Create User for Company A
        $user = User::withoutGlobalScopes()->updateOrCreate(
            ['email' => 'admin@companya.com'],
            [
                'company_id' => $company->id,
                'name' => 'Admin Company A',
                'first_name' => 'Admin',
                'last_name' => 'Company A',
                'password' => Hash::make('password'),
                'user_type' => 'backend',
                'is_active' => true,
            ]
        );
        $user->syncRoles(['super-admin']);
        
        $this->command->info("Created User: {$user->email}");

        // 3. Create Makers & Car Models
        $makersData = [
            'Toyota' => ['Camry', 'Corolla', 'Prius', 'RAV4'],
            'Honda' => ['Civic', 'Accord', 'CR-V'],
            'Ford' => ['Mustang', 'F-150', 'Explorer'],
        ];

        foreach ($makersData as $makerName => $models) {
            $maker = Maker::withoutGlobalScopes()->updateOrCreate(
                ['name' => $makerName, 'company_id' => $company->id],
                ['is_active' => true]
            );

            foreach ($models as $modelName) {
                CarModel::withoutGlobalScopes()->updateOrCreate(
                    [
                        'name' => $modelName,
                        'maker_id' => $maker->id,
                        'company_id' => $company->id
                    ],
                    ['is_active' => true]
                );
            }
        }
        $this->command->info("Seeded Makers and Car Models.");

        // 4. Create Colors
        $colors = [
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'Silver', 'hex_code' => '#C0C0C0'],
            ['name' => 'Red', 'hex_code' => '#FF0000'],
            ['name' => 'Blue', 'hex_code' => '#0000FF'],
        ];

        foreach ($colors as $color) {
            Color::withoutGlobalScopes()->updateOrCreate(
                ['name' => $color['name'], 'company_id' => $company->id],
                ['hex_code' => $color['hex_code'], 'is_active' => true]
            );
        }
        $this->command->info("Seeded Colors.");

        // 5. Update Fuels to belong to Company A
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
                'name' => 'Electric (EV)',
                'code' => 'EV',
                'description' => 'Fully electric vehicle powered by batteries.',
                'sort_order' => 5,
            ],
        ];

        foreach ($fuels as $fuel) {
            Fuel::withoutGlobalScopes()->updateOrCreate(
                ['code' => $fuel['code'], 'company_id' => $company->id],
                $fuel
            );
        }
        $this->command->info("Seeded Fuels for {$company->name}.");
    }
}
