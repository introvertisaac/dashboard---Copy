<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Ledger;

// Assuming the Ledger model is correctly set up
use Illuminate\Support\Facades\Session;

class CustomerTransactions extends Component
{
    use WithPagination;

    public $startDate;
    public $endDate;
    public $customers; // For filtering customers belonging to the logged-in user
    public $selectedCustomerId;
    public $selectedService;
    public $services;

    public $totalRevenue;
    public $totalExpense;
    public $profit;
    public $filteredTransactions; // Store filtered transactions
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
// Assuming you have a way to fetch customer IDs belonging to the logged-in user
        $this->customers = user()->customer->children;
        $this->services = config('billing.services');
    }

    public function updatedSelectedCustomerId()
    {
// Reset pagination when filters are applied
        $this->resetPage();
    }


    public function filterTransactions()
    {
        $this->resetPage(); // Reset pagination when filtering
    }



    public function render()
    {
        $customer_id = customer()->id;

        $query = Ledger::where('customer_id', $customer_id);

        // Apply date filters if selected
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('dated', [$this->startDate, $this->endDate]);
        }

        // Apply customer filter if selected
        if ($this->selectedCustomerId) {
            $query->where('initiating_customer_id', $this->selectedCustomerId)->where('customer_id', $customer_id);
        }

        if ($this->selectedService) {
            $query->where('service', $this->selectedService);
        }


        $this->totalRevenue = $query->sum('selling_price');
        $this->totalExpense = $query->sum('buying_price');
        $this->profit = $this->totalRevenue - $this->totalExpense;


        // Paginate the result
        $transactions = $query->orderBy('dated', 'desc')->paginate(10);

        return view('livewire.customer-transactions', [
            'transactions' => $transactions
        ]);
    }

}
