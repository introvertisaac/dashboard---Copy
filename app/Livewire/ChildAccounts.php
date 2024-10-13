<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class ChildAccounts extends Component
{

    use WithPagination;

    public $search = '';
    public $expandedCustomers = [];

    protected $paginationTheme = 'bootstrap';

    public function toggleChildAccounts($customerId)
    {
        if (in_array($customerId, $this->expandedCustomers)) {
            $this->expandedCustomers = array_diff($this->expandedCustomers, [$customerId]);
        } else {
            $this->expandedCustomers[] = $customerId;
        }
    }



    public function render()
    {
        $query = Customer::mine()->with('allChildAccounts');

        if (strlen($this->search) > 1) {
            $query = $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('website', 'like', '%' . $this->search . '%')
                ->orWhere('primary_email', 'like', '%' . $this->search . '%');
        }

        $customers = $query->paginate();

        return view('livewire.child-accounts', [
            'customers' => $customers,
        ]);
    }


}
