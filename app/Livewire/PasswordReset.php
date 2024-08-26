<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
class PasswordReset extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(
            ['username' => $this->email]
        );

        if ($status == Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));
        } else {
            session()->flash('error', __($status));
        }
    }

    public function render()
    {
        return view('livewire.password-reset')->layout('components.layout-guest');
    }
}
