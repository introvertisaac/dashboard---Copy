<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Ledger;
use Bavix\Wallet\Models\Transaction;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class TopupHistory extends Component
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
    public $totalDeposits;
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

        if ($this->selectedCustomerId) {
            $customer = Customer::mine()->where('id',$this->selectedCustomerId)->first();
        }else{
            $customer = customer();
        }


        $query = $customer->transactions()->where('type','deposit');


        if ($this->startDate && $this->endDate) {

            $startDate = Carbon::parse($this->startDate)->startOfDay();
            $endDate = Carbon::parse($this->endDate)->endOfDay();

            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $this->totalDeposits = $query->sum('amount');

        $transactions = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.topup-history', [
            'transactions' => $transactions
        ]);
    }
}
