<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;

class ClassifyResellerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:classify-reseller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Classifies reseller accounts based on the initial number of child customers they had added';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $customers = Customer::all();
        foreach ($customers as $customer) {
            $child_counts = $customer->children()->count();
            echo 'Running ' . $customer->name . PHP_EOL;
            if ($child_counts > 0) {
                $customer->is_reseller = 1;
                $customer->save();
                echo 'Updating ' . $customer->name . PHP_EOL;
            }

        }


    }
}
