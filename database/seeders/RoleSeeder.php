<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Products
            'products.view', 'products.create', 'products.edit', 'products.delete',
            // Categories
            'categories.view', 'categories.create', 'categories.edit', 'categories.delete',
            // Orders
            'orders.view', 'orders.create', 'orders.edit', 'orders.delete', 'orders.update_status',
            // Customers
            'customers.view', 'customers.edit', 'customers.delete',
            // Dashboard
            'dashboard.view', 'dashboard.analytics',
            // Banners
            'banners.view', 'banners.create', 'banners.edit', 'banners.delete',
            // Coupons
            'coupons.view', 'coupons.create', 'coupons.edit', 'coupons.delete',
            // Settings
            'settings.view', 'settings.edit',
            // Roles
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->givePermissionTo([
            'products.view', 'products.create', 'products.edit',
            'categories.view', 'categories.create', 'categories.edit',
            'orders.view', 'orders.edit', 'orders.update_status',
            'customers.view',
            'dashboard.view', 'dashboard.analytics',
            'banners.view', 'banners.create', 'banners.edit',
            'coupons.view', 'coupons.create', 'coupons.edit',
        ]);

        $staff = Role::firstOrCreate(['name' => 'Staff']);
        $staff->givePermissionTo([
            'products.view',
            'categories.view',
            'orders.view', 'orders.update_status',
            'customers.view',
            'dashboard.view',
        ]);

        Role::firstOrCreate(['name' => 'Customer']);
    }
}
