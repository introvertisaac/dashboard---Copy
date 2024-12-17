<?php

namespace App\Console\Commands;

use App\Mail\CustomerBalanceReport;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendCustomerBalanceReport extends Command
{
    protected $signature = 'app:send-balance-report {recipient_email?}';
    
    protected $description = 'Send customer balance report via email';

    public function handle()
    {
        try {
            $recipientEmail = $this->argument('recipient_email') ?? 'isaacw@identifyafrica.io';
            
            // Get the data directly using the exact SQL query that worked
            $customersData = DB::table('customers as c')
                ->join('wallets as w', 'w.holder_id', '=', 'c.id')
                ->where(function($query) {
                    $query->where('c.id', 2)
                          ->orWhere('c.parent_customer_id', 2);
                })
                ->select('c.id as customer_id', 'c.name', 'w.id as wallet_id', 'w.balance')
                ->orderBy('c.name')
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->name,
                        'balance' => $item->balance
                    ];
                });

            if ($customersData->isEmpty()) {
                Log::error('No customer data found');
                $this->error('No customer data found');
                return;
            }

            Mail::to($recipientEmail)->send(new CustomerBalanceReport($customersData));
            
            Log::info('Balance report sent successfully to ' . $recipientEmail);
            $this->info('Balance report sent successfully to ' . $recipientEmail);
            
        } catch (\Exception $e) {
            Log::error('Failed to send balance report: ' . $e->getMessage());
            $this->error('Failed to send balance report: ' . $e->getMessage());
        }
    }
}