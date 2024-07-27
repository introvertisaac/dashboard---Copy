<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Search;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    //

    public function index($customer_uuid = null)
    {
        $customer = $customer_uuid ? Customer::findByUuid($customer_uuid) : \customer();

        $balance = $customer->balance_label;

        $searches_count_today = Search::where('customer_id', $customer->id)->whereDate('created_at', Carbon::today())->count();
        $searches_count_month = Search::where('customer_id', $customer->id)->whereMonth('created_at', Carbon::now()->month)->count();
        $searches_count_year = Search::where('customer_id', $customer->id)->whereYear('created_at', Carbon::now()->year)->count();

        $customer_users_count = $customer->users()->count();
        $service_count = count($customer->api_list);

        $searches_count = Search::where('customer_id', $customer->id)->count();

        $activities = $customer->users()->first()->actions()->latest()->take(4)->get();

        $api_group_counts = Search::selectRaw('search_type, count(*) as count')
            ->groupBy('search_type')
            ->get();

       // dd($api_group_counts);

        return view('dashboard', compact('api_group_counts','customer','service_count', 'customer_users_count', 'customer_uuid', 'activities', 'balance', 'searches_count', 'searches_count_today', 'searches_count_month', 'searches_count_year'));
    }

}
