<?php

namespace App\Livewire;

use App\Models\Customer;
use Bavix\Wallet\Models\Transfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{

    use WithPagination;

    public $search = '';

    public $customer_uuid;
    public $customer_receiver_uuid;
    public $customer;
    public $page_view;
    public $primary_email;
    public $name;
    public $website;
    public $phone;
    public $status;
    public $charges = [];
    public $is_reseller;

    public $amount;
    public $transfer_amount;
    public $my_customers = [];
    public $narration;
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

    public function mount()
    {
        if (is_reseller() == false) {
            abort(403, 'This customer cannot access this page.');
        }
    }

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

    public function allocate($uuid)
    {
        try {
            $customer = Customer::findByUuid($uuid);
            if (!$customer) {
                session()->flash('error', 'Customer not found');
            } else {

                $this->customer_uuid = $uuid;
                $this->customer = $customer;
                $this->page_view = 'allocate-credit';
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong ');
        }


    }

    public function credit_topup()
    {
        $customer = Customer::findByUuid($this->customer_uuid);
        $amount = $this->amount;
        $narration = $this->narration;
        $customer_name = $customer->name;

        $customer->wallet->deposit($amount, ['narration' => $narration, 'ip' => ip(), 'user_id' => user('id'), 'customer_id' => $this->customer->id]);

        trail('credit-topup', "Credit topup of $amount for " . $customer_name, $customer);

        session()->flash('message', "Credit allocated successfully to $customer_name New balance: " . $customer->balance_label);

        $this->list();
    }

    public function transfer($uuid)
    {
        try {
            $customer = Customer::findByUuid($uuid);
            if (!$customer) {
                session()->flash('error', 'Customer not found');
            } else {
                $customers = Customer::where('id', '!=', $customer->id)->mine()->order()->get();
                $this->my_customers = $customers;

                $this->transfer_amount = $customer->balance;

                $this->customer_uuid = $uuid;
                $this->customer = $customer;
                $this->page_view = 'transfer-credit';
            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong ');
        }


    }

    public function credit_transfer()
    {
        $customer = Customer::findByUuid($this->customer_uuid);

        $customer_receiver = Customer::findByUuid($this->customer_receiver_uuid);
        $customer_receiver_wallet = $customer_receiver->wallet;
        $amount = $this->transfer_amount;
        $narration = $this->narration;
        $customer_name = $customer_receiver->name;


        try {
            $transfer = $customer->wallet->transfer($customer_receiver_wallet, $amount, ['narration' => $narration, 'ip' => ip(), 'user_id' => user('id'), 'customer_id' => $this->customer->id]);

            if ($transfer instanceof Transfer) {
                trail('credit-transfer', "Credit topup of $amount to " . $customer_name, $customer);

                session()->flash('message', "Credit transferred successfully to $customer_name New balance: " . $customer_receiver->balance_label);

                $this->list();
            } else {
                session()->flash('message', "Credit transferred failed");
            }

        } catch (\Bavix\Wallet\Exceptions\InsufficientFunds $e) {

            session()->flash('message', "Credit transferred failed due to Insufficient funds for this transfer.");

        } catch (\Exception $e) {
            // Catch any other exception and log it for debugging
            session()->flash('message', "An error occurred while processing the transfer. Please try again.");

        }


    }


    public function new_customer()
    {
        $this->validate();

        $customer = Customer::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_email' => $this->primary_email,
            'status' => $this->status,
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

        $current_status = $customer->status;

        $customer->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'primary_email' => $this->primary_email,
            'status' => $this->status,
            'api_count' => count(config('billing.services')),
            'created_by' => user('id'),
            'charges' => $this->charges,
            'is_reseller' => boolval($this->is_reseller),
            'parent_customer_id' => \customer()->id
        ]);


        trail('customer-updated', 'Customer updated successfully', $customer);


        if (($this->status === 'suspended') && ($current_status !== 'suspended')) {

            $customer_users = $customer->users()->get();
           // $customer_customers = $customer->children()->get();
            foreach ($customer_users as $customer_user) {
                DB::table('sessions')->where('user_id', $customer_user->id)->delete();
            }

            session()->flash('message', 'Customer account has been suspended and associated users have been logged out. Any child account of the customer will also be suspended');
        } else {
            session()->flash('message', 'Customer updated successfully');
        }


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
                $this->status = $customer->status;
                $this->website = $customer->website;
                $this->is_reseller = $customer->is_reseller;
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
