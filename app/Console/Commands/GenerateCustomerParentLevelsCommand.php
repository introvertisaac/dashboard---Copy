<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class GenerateCustomerParentLevelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:parent-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //

        $customers = Customer::orderBy('id', 'DESC')->get();
        foreach ($customers as $customer) {
            $customer->generateParentLevels();

        }


    }
}
