<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $system_customer = [
            'name' => 'System Customer',
            'phone' => '254712345678',
            'primary_email' => 'system@example.com',
            'uuid' => uuid(),
            'api_count' => count(config('billing.services')),
            'created_by' => 1,
            'parent_customer_id' => 1,
            'is_reseller' => 1
        ];

        Customer::create($system_customer);

    }
}
