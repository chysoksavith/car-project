<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // # Define all permissions
        $permissions = [
            // Users
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            // Roles
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            // Permissions
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            // Locations
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',
            // Companies
            'companies.view',
            'companies.create',
            'companies.edit',
            'companies.delete',
            // Fuels
            'fuels.view',
            'fuels.create',
            'fuels.edit',
            'fuels.delete',
            // Makers
            'makers.view',
            'makers.create',
            'makers.edit',
            'makers.delete',
            // Car Models
            'car_models.view',
            'car_models.create',
            'car_models.edit',
            'car_models.delete',
            // Colors
            'colors.view',
            'colors.create',
            'colors.edit',
            'colors.delete',
            // Suppliers
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            // Inspection Items (master list)
            'inspection_items.view',
            'inspection_items.create',
            'inspection_items.edit',
            'inspection_items.delete',
            // Car Inspections (per-car repair records)
            'car_inspections.view',
            'car_inspections.create',
            'car_inspections.edit',
            'car_inspections.delete',
            // Containers
            'containers.view',
            'containers.create',
            'containers.edit',
            'containers.delete',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // # Define roles and assign permissions

        // Super Admin — bypass all gates (handled in AuthServiceProvider)
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);

        // Admin — full user + role management, but cannot manage permissions
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'locations.view',
            'locations.create',
            'locations.edit',
            'locations.delete',
            'companies.view',
            'companies.create',
            'companies.edit',
            'companies.delete',
            'fuels.view',
            'fuels.create',
            'fuels.edit',
            'fuels.delete',
            'makers.view',
            'makers.create',
            'makers.edit',
            'makers.delete',
            'car_models.view',
            'car_models.create',
            'car_models.edit',
            'car_models.delete',
            'colors.view',
            'colors.create',
            'colors.edit',
            'colors.delete',
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',
            'inspection_items.view',
            'inspection_items.create',
            'inspection_items.edit',
            'inspection_items.delete',
            'car_inspections.view',
            'car_inspections.create',
            'car_inspections.edit',
            'car_inspections.delete',
            'containers.view',
            'containers.create',
            'containers.edit',
            'containers.delete',
        ]);

        // Editor — read-only + edit users
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions([
            'users.view',
            'users.edit',
            'roles.view',
            'permissions.view',
            'companies.view',
            'companies.edit',
            'fuels.view',
            'fuels.edit',
            'makers.view',
            'makers.edit',
            'car_models.view',
            'car_models.edit',
            'colors.view',
            'colors.edit',
            'suppliers.view',
            'suppliers.edit',
            'inspection_items.view',
            'inspection_items.edit',
            'car_inspections.view',
            'car_inspections.edit',
            'containers.view',
            'containers.edit',
        ]);

        // Viewer — read-only access
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);
        $viewer->syncPermissions([
            'users.view',
            'roles.view',
            'permissions.view',
            'companies.view',
            'fuels.view',
            'makers.view',
            'car_models.view',
            'colors.view',
            'suppliers.view',
            'inspection_items.view',
            'car_inspections.view',
            'containers.view',
        ]);

        $this->command->info('Roles and permissions seeded.');
    }
}
