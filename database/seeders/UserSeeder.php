<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Seed default application users.
     *
     * Uses updateOrCreate — safe to run multiple times without duplicates.
     */
    public function run(): void
    {
        $users = [
            [
                'data' => [
                    'email'      => 'admin@saas.com',
                    'name'       => 'Admin User',
                    'first_name' => 'Admin',
                    'last_name'  => 'User',
                    'password'   => bcrypt('password'),
                    'user_type'  => 'backend',
                    'is_active'  => true,
                ],
                'role' => 'super-admin',
            ],

            // ── Add more default users here ────────────────────────────
            // [
            //     'data' => [
            //         'email'      => 'editor@saas.com',
            //         'name'       => 'Editor User',
            //         'first_name' => 'Editor',
            //         'last_name'  => 'User',
            //         'password'   => bcrypt('password'),
            //         'user_type'  => 'backend',
            //         'is_active'  => true,
            //     ],
            //     'role' => 'editor',
            // ],
        ];

        foreach ($users as $entry) {
            $user = User::updateOrCreate(
                ['email' => $entry['data']['email']],
                $entry['data']
            );

            $user->syncRoles([$entry['role']]);

            $this->command->info(
                "  ✓ {$entry['data']['email']} → role: {$entry['role']}"
            );
        }
    }
}
