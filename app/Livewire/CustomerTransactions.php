<?php

namespace App\Livewire;

use Illuminate\Support\Carbon;
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

        $query = Ledger::where('customer_id', $customer_id);


        if ($this->startDate && $this->endDate) {

            $startDate = Carbon::parse($this->startDate)->startOfDay();
            $endDate = Carbon::parse($this->endDate)->endOfDay();

            $query->whereBetween('dated', [$startDate, $endDate]);
        }

        if ($this->selectedCustomerId) {
            $query->where('initiating_customer_id', $this->selectedCustomerId)->where('customer_id', $customer_id);
        }

        if ($this->selectedService) {
            $query->where('service', $this->selectedService);
        }


        $this->totalRevenue = $query->sum('selling_price');
        $this->totalExpense = $query->sum('buying_price');
        $this->profit = $this->totalRevenue - $this->totalExpense;


        $transactions = $query->orderBy('dated', 'desc')->paginate(10);

        return view('livewire.customer-transactions', [
            'transactions' => $transactions
        ]);
    }

}
