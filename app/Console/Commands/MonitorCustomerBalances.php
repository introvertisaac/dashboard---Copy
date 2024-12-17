<?php

namespace App\Console\Commands;

use App\Mail\CustomerBalanceReport;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MonitorCustomerBalances extends Command
{
    protected $signature = 'app:monitor-balances';
    
    protected $description = 'Monitor customer balances and send alerts for low balances';

    private const THRESHOLD = 5000;
    private const NOTIFICATION_EMAIL = 'isaacw@identifyafrica.io';

    public function handle()
    {
        try {
            // Get customers with low balances
            $lowBalanceCustomers = DB::table('customers as c')
                ->join('wallets as w', 'w.holder_id', '=', 'c.id')
                ->where(function($query) {
                    $query->where('c.id', 2)
                          ->orWhere('c.parent_customer_id', 2);
                })
                ->where('w.balance', '<', self::THRESHOLD)
                ->where('c.status', 'active')
                ->select('c.id as customer_id', 'c.name', 'w.balance')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->name,
                        'balance' => $item->balance
                    ];
                });

            if ($lowBalanceCustomers->isNotEmpty()) {
                Mail::to(self::NOTIFICATION_EMAIL)
                    ->send(new CustomerBalanceReport($lowBalanceCustomers, 'Low Balance Alert - ' . $lowBalanceCustomers->count() . ' Customers'));

                Log::info('Low balance alert sent for ' . $lowBalanceCustomers->count() . ' customers');
            }
            
        } catch (\Exception $e) {
            Log::error('Failed to monitor balances: ' . $e->getMessage());
            $this->error('Failed to monitor balances: ' . $e->getMessage());
        }
    }
}