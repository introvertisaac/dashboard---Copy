<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Search;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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

        $api_group_counts = Search::where('customer_id', $customer->id)->selectRaw('search_type, count(*) as count')
            ->groupBy('search_type')
            ->orderBy('count','DESC')
            ->get();

        $search_chart = Search::where('customer_id', $customer->id)->select([
            DB::raw('DATE(created_at) AS date'),
            DB::raw('COUNT(id) AS count'),
        ])
            ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()])
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->toArray();


        $keys = [];
        $values = [];
        foreach ($search_chart as $key => $item) {
            $keys[] = CarbonImmutable::parse($item['date'])->format('M d');
            $values[] = $item['count'];
        }

        $chart_keys = implode(',', array_map('add_quotes', $keys));

        $chart_values = implode(',', $values);

        return view('dashboard', compact('chart_values','chart_keys','api_group_counts','customer','service_count', 'customer_users_count', 'customer_uuid', 'activities', 'balance', 'searches_count', 'searches_count_today', 'searches_count_month', 'searches_count_year'));
    }

}
