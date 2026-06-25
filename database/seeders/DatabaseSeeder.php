<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Order matters — roles/permissions must exist before users are assigned roles.
     * To add more seeders in the future, simply append them to the array below.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            FuelSeeder::class,
            LocationSeeder::class,
            DemoDataSeeder::class,
            InspectionCategorySeeder::class,
        ]);
    }
}
