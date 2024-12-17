<?php

namespace App\Console\Commands;

use App\Mail\CustomerBalanceReport;
use App\Models\Customer;
use App\Models\UserNotificationSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MonitorCustomerBalances extends Command
{
    protected $signature = 'app:monitor-balances';
    
    protected $description = 'Monitor customer balances and send alerts based on user preferences';

    private function getAdminEmail()
    {
        return env('ADMIN_NOTIFICATION_EMAIL', 'isaacw@identifyafrica.io');
    }

    public function handle()
    {
        try {
            $settings = UserNotificationSetting::where('balance_alerts', true)
                ->with(['user.customer'])
                ->get();

            $this->info('===== Debug Info =====');
            $this->info('Current time: ' . now()->format('Y-m-d H:i:s'));
            
            foreach ($settings as $setting) {
                $this->info("\nUser Details:");
                $this->info("Email: " . $setting->user->username);
                $this->info("Alert Frequency: " . $setting->alert_frequency);
                $this->info("Preferred Time: " . $setting->preferred_time);
                $this->info("Current Balance: " . number_format($setting->user->customer->wallet->balance) . " KES");
                $this->info("Threshold: " . number_format($setting->balance_threshold) . " KES");
                
                if (!$this->shouldSendAlert($setting)) {
                    $this->info('❌ Not sending alert - Time condition not met');
                    $this->info('Expected time: ' . $setting->preferred_time);
                    $this->info('Current time: ' . now()->format('H:i:s'));
                } else {
                    $this->info('✓ Time condition met - Sending alert');
                    
                    // Get balance data
                    $lowBalanceCustomers = collect([
                        [
                            'name' => $setting->user->customer->name,
                            'balance' => $setting->user->customer->wallet->balance
                        ]
                    ]);

                    // Send to both user and admin
                    $recipients = collect([$setting->user->username, $this->getAdminEmail()])->unique();
                    
                    foreach ($recipients as $email) {
                        Mail::to($email)->send(new CustomerBalanceReport(
                            $lowBalanceCustomers,
                            'Low Balance Alert - Balance Below ' . number_format($setting->balance_threshold) . ' KES'
                        ));
                    }

                    $this->info('✓ Alert emails sent to: ' . implode(', ', $recipients->toArray()));
                }
            }
        } catch (\Exception $e) {
            $this->error($e->getMessage());
            Log::error('Failed to monitor balances: ' . $e->getMessage());
        }
    }

    private function shouldSendAlert($setting): bool
    {
        $now = now();
        
        switch ($setting->alert_frequency) {
            case 'hourly':
                return true;
                
            case 'daily':
                return $setting->preferred_time && 
                       $now->format('H:i') === Carbon::parse($setting->preferred_time)->format('H:i');
                
            case 'weekly':
                return $setting->preferred_time && 
                       $now->format('H:i') === Carbon::parse($setting->preferred_time)->format('H:i') && 
                       $now->dayOfWeek === 1;
                
            default:
                return false;
        }
    }
}