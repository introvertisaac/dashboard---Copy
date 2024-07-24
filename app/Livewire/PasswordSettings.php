<?php

namespace App\Livewire;

use Livewire\Component;

class PasswordSettings extends Component
{

    public $new_password;
    public $confirm_password;


    protected $rules = [
        'new_password' => ['required', 'string', 'min:8', 'same:confirm_password'],
        'confirm_password' => ['required', 'string', 'min:8', 'same:new_password'],
    ];


    public function password_settings()
    {
        $this->validate();
        $user = \user();
        $user->update([
            'password' => $this->new_password
        ]);

        trail('password-changed-updated', 'Changed password', $user);

        session()->flash('message', 'Your password has changed. Please use your new password to log in.');
        logout_all_guards();

        return redirect()->route('login');
    }


    public function render()
    {
        return view('livewire.password-settings');
    }


}
