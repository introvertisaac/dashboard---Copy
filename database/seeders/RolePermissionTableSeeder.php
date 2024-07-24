<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $permissions = [
            'view_customers',
            'create_customers',
            'manage_customers',
            'create_users',
            'view_balance',
            'perform_searches',
            'manage_users',
            'manage_api_settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }


        $role = Role::create([
            'name' => 'Super Admin'
        ]);

        $all_permissions = Permission::all();
        $role->givePermissionTo($all_permissions);


    }
}
