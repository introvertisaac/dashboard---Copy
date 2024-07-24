<?php

namespace App\Http\Controllers;


use App\Models\User;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class GuestController extends Controller
{
    //

    public function login()
    {
        return view('login');
    }

    public function index()
    {
        if (Auth::check()) {


            return redirect()->route('dashboard');
        }

        return $this->login();
    }


    public function logout()
    {
        trail('logout', 'Logged Out');
        logout_all_guards();

        flash(__("Successfully logged out"))->info();


        return redirect()->route('index');
    }


}
