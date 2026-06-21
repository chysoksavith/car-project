<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InspectionItem;

class InspectionCategorySeeder extends Seeder
{
    /**
     * Seed the simplified inspection items.
     */
    public function run(): void
    {
        // Clear old ones just in case
        InspectionItem::truncate();

        $data = [
            'មេកានិច' => [
                ['name_kh' => 'បូមមុខ ក្រោយ',           'name_en' => 'Front/Rear Brake Pump',    'default_price' => 15.00],
                ['name_kh' => 'ទម្លាក់កុងទ័រ',           'name_en' => 'Shock Absorber',           'default_price' => 20.00],
                ['name_kh' => 'ចង្កូតចំហៀង (CV Joint)',  'name_en' => 'CV Joint',                 'default_price' => 25.00],
                ['name_kh' => 'ចង្ក្រានហ្វ្រ័',          'name_en' => 'Brake Disc / Drum',        'default_price' => 18.00],
                ['name_kh' => 'ថ្នាំប្រេងម៉ាស៊ីន',       'name_en' => 'Engine Oil',               'default_price' =>  8.00],
                ['name_kh' => 'ហ្វីតទ្រ័ (Filter)',       'name_en' => 'Oil / Air Filter',         'default_price' =>  5.00],
                ['name_kh' => 'ក្រណាត់ (Timing Belt)',    'name_en' => 'Timing Belt',               'default_price' => 30.00],
                ['name_kh' => 'ប្រេងម៉ាស៊ីនក្តៅ (Coolant)','name_en' => 'Coolant / Radiator Fluid', 'default_price' =>  6.00],
            ],
            'អគ្គិសនី' => [
                ['name_kh' => 'ថ្ម (Battery)',               'name_en' => 'Battery',              'default_price' => 40.00],
                ['name_kh' => 'AC / ម៉ាស៊ីនត្រជាក់',         'name_en' => 'Air Conditioning',    'default_price' => 30.00],
                ['name_kh' => 'ម៉ូទ័រ AC (Compressor)',       'name_en' => 'AC Compressor',       'default_price' => 80.00],
                ['name_kh' => 'ជំពូកភ្លើង (Spark Plugs)',    'name_en' => 'Spark Plugs',         'default_price' =>  3.00],
                ['name_kh' => 'បំភ្លឺទូទៅ (Lights)',          'name_en' => 'General Lighting',    'default_price' =>  5.00],
                ['name_kh' => 'ម៉ូទ័រ Alternator',            'name_en' => 'Alternator',          'default_price' => 50.00],
            ],
            'ខ្លួនរថយន្ត' => [
                ['name_kh' => 'កញ្ចក់ (Windshield)',          'name_en' => 'Windshield',          'default_price' =>  0.00],
                ['name_kh' => 'ទ្វារ (Doors)',                'name_en' => 'Doors',               'default_price' =>  0.00],
                ['name_kh' => 'ខ្លួនរថយន្ត (Body Panel)',     'name_en' => 'Body Panel',          'default_price' =>  0.00],
                ['name_kh' => 'ពណ៌ (Paint)',                  'name_en' => 'Paint Condition',     'default_price' =>  0.00],
                ['name_kh' => 'កែវទ្វារ (Window Glass)',       'name_en' => 'Window Glass',        'default_price' =>  0.00],
            ],
            'កង់ / ហ្វ្រ័' => [
                ['name_kh' => 'កង់ (Tyres)',                  'name_en' => 'Tyres',               'default_price' =>  0.00],
                ['name_kh' => 'ហ្វ្រ័ (Brake Pads)',          'name_en' => 'Brake Pads',          'default_price' => 12.00],
                ['name_kh' => 'ហ្វ្រ័ដៃ (Handbrake)',          'name_en' => 'Handbrake',           'default_price' =>  0.00],
                ['name_kh' => 'ចក្រ (Wheel Bearing)',         'name_en' => 'Wheel Bearing',       'default_price' => 15.00],
            ],
            'ផ្ទៃក្នុង' => [
                ['name_kh' => 'កៅអីមុខ ក្រោយ',               'name_en' => 'Front/Rear Seats',    'default_price' =>  0.00],
                ['name_kh' => 'ផ្ទាំងដៃ (Dashboard)',         'name_en' => 'Dashboard',           'default_price' =>  0.00],
                ['name_kh' => 'ប្រព័ន្ធសំឡេង (Audio)',        'name_en' => 'Audio System',        'default_price' =>  0.00],
                ['name_kh' => 'ភ្លើងសំណាង (Interior Lights)', 'name_en' => 'Interior Lights',    'default_price' =>  0.00],
            ],
        ];

        $count = 0;
        foreach ($data as $category => $items) {
            // Create the parent item
            $parent = InspectionItem::withoutGlobalScopes()->updateOrCreate(
                [
                    'company_id' => null,
                    'parent_id'  => null,
                    'name_kh'    => $category
                ],
                [
                    'name_en'       => null,
                    'default_price' => 0
                ]
            );
            $count++;

            // Create the children
            foreach ($items as $itemData) {
                InspectionItem::withoutGlobalScopes()->updateOrCreate(
                    [
                        'company_id' => null,
                        'parent_id'  => $parent->id,
                        'name_kh'    => $itemData['name_kh']
                    ],
                    $itemData
                );
                $count++;
            }
        }

        $this->command->info("Simplified inspection items seeded ({$count} items).");
    }
}
