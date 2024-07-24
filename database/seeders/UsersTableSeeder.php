<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $super_user = User::create([
            'name' => 'Super Admin',
            'username' => 'super@account.admin',
            'password' => 'Super++@KYC24',
            'type' => 'ui',
            'is_super_admin' => true,
            'customer_id' => 1,
            'created_by' => 1,
            'uuid' => uuid()
        ]);

        $super_user->assignRole('Super Admin');

    }
}
