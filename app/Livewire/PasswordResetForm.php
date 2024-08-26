<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class PasswordResetForm extends Component
{
    public $username;
    public $token;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'username' => 'required',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount($token, $username)
    {
        $this->token = $token;
        $this->username = $username;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            $this->only('username', 'password', 'password_confirmation', 'token'),
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', __($status));
            return redirect()->route('login');
        } else {
            session()->flash('error', __($status));
        }
    }

    public function render()
    {
        return view('livewire.password-reset-form')->layout('components.layout-guest');
    }
}
