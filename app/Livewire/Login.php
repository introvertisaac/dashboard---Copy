<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Login extends Component
{
    public $username;
    public $password;

    protected $rules = [
        'username' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $credentials = $this->validate();

        if (Auth::attempt($credentials)) {
            $user = user();
            $customer = $user->customer;

            if ($customer->status != Customer::ACTIVE) {
                session()->flash('error', 'Customer Account Disabled');
                logout_all_guards();
                return false;
            }

            if ($user->status != User::ACTIVE) {
                session()->flash('error', 'Your user account is currently disabled');
                logout_all_guards();
                return false;
            }

            set_customer_session($customer);

            trail('login-success', 'Logged in Successfully');

            session()->flash('message', 'Welcome to ' . config('app.name'));
            return redirect()->intended('dashboard');
        }

        session()->flash('error', 'The provided credentials are incorrect.');
    }

    public function render()
    {
        return view('livewire.login');
    }
}
