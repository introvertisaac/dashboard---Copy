<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ledger;

use Illuminate\Support\Facades\Session;

class CustomerTransactions extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $customers;
    public $selectedCustomerId;
    public $selectedService;
    public $services;

    public $totalRevenue;
    public $totalExpense;
    public $profit;
    public $filteredTransactions;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->customers = user()->customer->children;
        $this->services = config('billing.services');
    }

    public function updatedSelectedCustomerId()
    {

        $this->resetPage();
    }


    public function filterTransactions()
    {
        $this->resetPage();
    }

    public function render()
    {
       $customer_id = customer()->id;
       
       $query = Ledger::with(['customer', 'user'])
           ->where('customer_id', $customer_id);
    
       $revenueQuery = clone $query;
       $revenueQuery->where('initiating_customer_id', '!=', $customer_id);
    
       if ($this->startDate && $this->endDate) {
           $startDate = Carbon::parse($this->startDate)->startOfDay();
           $endDate = Carbon::parse($this->endDate)->endOfDay();
           $query->whereBetween('dated', [$startDate, $endDate]);
           $revenueQuery->whereBetween('dated', [$startDate, $endDate]);
       }
    
       if ($this->selectedCustomerId) {
           if ($this->selectedCustomerId === 'merged') {
               $query->where('initiating_customer_id', '!=', $customer_id);
           } else if ($this->selectedCustomerId == $customer_id) {
               $query->where('initiating_customer_id', $customer_id);
               $revenueQuery->where('initiating_customer_id', $customer_id);
           } else {
               $selectedCustomer = Customer::find($this->selectedCustomerId);
               
               if ($selectedCustomer && $selectedCustomer->is_reseller) {
                   $whereCondition = function($q) use ($selectedCustomer) {
                       $q->where('initiating_customer_id', $selectedCustomer->id)
                         ->orWhereExists(function ($subquery) use ($selectedCustomer) {
                             $subquery->select('id')
                                     ->from('customers')
                                     ->whereColumn('customers.id', 'ledgers.initiating_customer_id')
                                     ->whereRaw('JSON_CONTAINS(customers.parent_levels, ?)', [
                                         json_encode(['id' => $selectedCustomer->id])
                                     ]);
                         });
                   };
                   $query->where($whereCondition);
                   $revenueQuery->where($whereCondition);
               } else {
                   $query->where('initiating_customer_id', $this->selectedCustomerId);
                   $revenueQuery->where('initiating_customer_id', $this->selectedCustomerId);
               }
           }
       }
    
       if ($this->selectedService) {
           $query->where('service', $this->selectedService);
           $revenueQuery->where('service', $this->selectedService);
       }
       
       $this->totalRevenue = $revenueQuery->sum('selling_price');
       $this->totalExpense = $revenueQuery->sum('buying_price');
       $this->profit = $this->totalRevenue - $this->totalExpense;
    
       $transactions = $query->orderBy('dated', 'desc')->paginate(10);
    
       return view('livewire.customer-transactions', [
           'transactions' => $transactions
       ]);
    }
}
