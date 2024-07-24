<?php

namespace App\Livewire;

use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{

    use WithPagination;

    public $search = '';

    public $customer_uuid;
    public $customer;
    public $page_view;
    public $primary_email;
    public $name;
    public $website;
    public $phone;
    public $charges = [];
    public $is_reseller;
    protected $paginationTheme = 'bootstrap';
    /**
     * @var true
     */
    public bool $editCustomer;

    protected $listeners = ['list' => 'render'];

    protected $rules = [
        'primary_email' => 'required|email',
        'name' => 'required',
        'phone' => 'required',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }


    protected $queryString = ['search'];


    public function create()
    {
        $this->page_view = 'create-customer';
    }

    public function list()
    {
        $this->page_view = '';
        $this->reset();

    }


    public function edit($uuid)
    {
        try {
            $customer = Customer::findByUuid($uuid);
            if (!$customer) {
                session()->flash('error', 'Customer not found');
            } else {

                $this->customer_uuid = $uuid;
                $this->customer = $customer;
                $this->page_view = 'edit-customer';
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong ');
        }


    }


    public function new_customer()
    {
        $this->validate();

        // Log::info('customer', ['this' => $this]);
        Log::info('new', ['charges' => $this->charges]);

        $customer = Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_email' => $this->primary_email,
            'uuid' => uuid(),
            'api_count' => count(config('billing.services')),
            'created_by' => user('id'),
            'charges' => $this->charges,
            'is_reseller' => boolval($this->is_reseller),
            'parent_customer_id' => \customer()->id
        ]);

        trail('customer-created', 'Customer created successfully', $customer);

        session()->flash('message', 'Customer create successfully. Add users for the new customer.');

        $this->list();
    }

    public function update_customer()
    {
        $this->validate();

        $customer = Customer::findByUuid($this->customer_uuid);

        Log::info('update', ['charges' => $this->charges]);

        $customer->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_email' => $this->primary_email,
            'api_count' => count(config('billing.services')),
            'created_by' => user('id'),
            'charges' => $this->charges,
            'is_reseller' => boolval($this->is_reseller),
            'parent_customer_id' => \customer()->id
        ]);


        trail('customer-updated', 'Customer updated successfully', $customer);

        session()->flash('message', 'Customer updated successfully');

        $this->list();
    }

    public function render()
    {

        if ($this->page_view) {

            $services = config('billing.services');

            $customer_charges = \customer()->charges;

            if ($this->customer) {
                $customer = $this->customer;

                $this->name = $customer->name;
                $this->primary_email = $customer->primary_email;
                $this->phone = $customer->phone;
                $this->website = $customer->website;
                $this->charges = (is_null($customer->charges)) ? [] : $customer->charges;
            }

            return view('livewire.' . $this->page_view, compact('services', 'customer_charges'));
        }


        if (strlen($this->search) > 1) {
            $customers = Customer::mine()->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('website', 'like', '%' . $this->search . '%')
                ->orWhere('primary_email', 'like', '%' . $this->search . '%')
                ->paginate();


        } else {

            $customers = Customer::mine()->paginate();
        }

        return view('livewire.customers', [
            'customers' => $customers,
        ]);


    }


}
