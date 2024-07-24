<?php

namespace App\Livewire;

use Livewire\Component;

class AccountSettings extends Component
{

    public $name;
    public $phone;

    protected $rules = [
        'name' => 'required',
        'phone' => 'required',
    ];


    public function account_settings()
    {
        $this->validate();

        user()->update([
            'name' => $this->name,
            'phone' => $this->phone,
        ]);

        trail('account-settings-updated', 'Updated Account Settings');

        session()->flash('message', 'Account settings have been updated');


    }

    public function render()
    {
        $user = user();
        $this->name =$user->name;
        $this->phone =$user->phone;


        return view('livewire.account-settings');
    }
}
