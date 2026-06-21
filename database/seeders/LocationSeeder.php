<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Location;
use Illuminate\Support\Facades\File;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jsonPath = database_path('data/location.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("The locations.json file does not exist at: {$jsonPath}");
            $this->command->info("Please create it and paste your JSON array before seeding.");
            return;
        }

        $json = File::get($jsonPath);
        $locations = json_decode($json, true);

        if (!$locations) {
            $this->command->error("Invalid JSON format in locations.json");
            return;
        }

        $this->command->info('Truncating old locations...');
        Location::query()->delete();

        $this->command->info('Seeding locations. This might take a moment...');

        // Insert in chunks of 1000 to prevent memory exhaustion and speed up insertion
        $chunks = array_chunk($locations, 1000);

        foreach ($chunks as $chunk) {
            $insertData = [];
            foreach ($chunk as $location) {
                $insertData[] = [
                    'code'        => $location['code'],
                    'name_km'     => $location['name_km'],
                    'name_en'     => $location['name_en'],
                    'type'        => $location['type'],
                    'type_km'     => $location['type_km'] ?? null,
                    'type_en'     => $location['type_en'] ?? null,
                    'parent_code' => $location['parent_code'] ?? null,
                    'reference'   => $location['reference'] ?? null,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }

            Location::insert($insertData);
        }

        $this->command->info('Locations seeded successfully! (' . count($locations) . ' records)');
    }
}
