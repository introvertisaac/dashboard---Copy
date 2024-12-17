<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserNotificationSetting;

class AccountSettings extends Component
{
    public $name;
    public $phone;
    public $balance_alerts = true;
    public $balance_threshold;
    public $alert_frequency = 'weekly';
    public $preferred_time;

    protected $rules = [
        'name' => 'required',
        'phone' => 'required',
        'balance_alerts' => 'boolean',
        'balance_threshold' => 'nullable|numeric|min:0',
        'alert_frequency' => 'required|in:hourly,daily,weekly',
        'preferred_time' => 'nullable|required_unless:alert_frequency,hourly',
    ];

    public function mount()
    {
        $user = user();
        $this->name = $user->name;
        $this->phone = $user->phone;
    
        // Load notification settings
        $settings = UserNotificationSetting::firstOrCreate(
            ['user_id' => $user->id],
            [
                'balance_alerts' => true,
                'balance_threshold' => 5000,
                'alert_frequency' => 'weekly',
                'preferred_time' => '09:00:00' 
            ]
        );
    
        $this->balance_alerts = $settings->balance_alerts;
        $this->balance_threshold = $settings->balance_threshold;
        $this->alert_frequency = $settings->alert_frequency;
        $this->preferred_time = $settings->preferred_time;
    }

    public function account_settings()
    {
        $this->validate();

        user()->update([
            'name' => $this->name,
            'phone' => $this->phone,
        ]);

        UserNotificationSetting::updateOrCreate(
            ['user_id' => user()->id],
            [
                'balance_alerts' => $this->balance_alerts,
                'balance_threshold' => $this->balance_threshold,
                'alert_frequency' => $this->alert_frequency,
                'preferred_time' => $this->preferred_time,
            ]
        );

        trail('account-settings-updated', 'Updated Account Settings');
        session()->flash('message', 'Account settings have been updated');
    }

    public function render()
    {
        return view('livewire.account-settings');
    }
}