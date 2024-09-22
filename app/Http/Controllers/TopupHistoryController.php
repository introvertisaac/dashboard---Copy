<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TopupHistoryController extends Controller
{
    //

    public function index()
    {
        return view('reports.topup-history');
    }

}
