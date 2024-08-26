<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerTransactionController extends Controller
{
    //
    public function index()
    {
        return view('reports.customer-transactions');
    }

}
