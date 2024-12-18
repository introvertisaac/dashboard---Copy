<?php

namespace App\Livewire;

use App\Models\Customer;
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
    public $totalDeposits;
    public $currentBalance;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->customers = user()->customer->children;
        $this->selectedCustomerId = customer()->id; 
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
        if ($this->selectedCustomerId === '') {
            // All Customers (Inc Self)
            $baseCustomers = Customer::mine()
                ->orWhere('id', customer()->id)
                ->get();
            $query = Transaction::where('type', 'deposit')
                ->whereHasMorph(
                    'payable',
                    [Customer::class],
                    function($q) {
                        $q->where('id', customer()->id)
                            ->orWhereIn('id', Customer::mine()->pluck('id'));
                    }
                );
            $this->currentBalance = $baseCustomers->sum(function($customer) {
                return $customer->wallet->balance;
            });
        } else if ($this->selectedCustomerId === 'merged') {
            // All except self
            $baseCustomers = Customer::mine()
                ->where('id', '!=', customer()->id)
                ->get();
            $query = Transaction::where('type', 'deposit')
                ->whereHasMorph(
                    'payable',
                    [Customer::class],
                    function($q) {
                        $q->whereIn('id', Customer::mine()
                            ->where('id', '!=', customer()->id)
                            ->pluck('id'));
                    }
                );
            $this->currentBalance = $baseCustomers->sum(function($customer) {
                return $customer->wallet->balance;
            });
        } else {
            // Single customer or default
            $customer = $this->selectedCustomerId ? 
                Customer::mine()->where('id', $this->selectedCustomerId)->first() : 
                customer();
                
            if (!$customer) {
                $customer = customer();
            }
            
            $query = $customer->transactions()->where('type', 'deposit');
            $this->currentBalance = $customer->wallet->balance;
        }
    
        // Date filtering
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