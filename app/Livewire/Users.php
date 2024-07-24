<?php

namespace App\Livewire;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Users extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search = '';

    public $user_uuid;
    public $user;
    public $page_view;
    public $primary_email;
    public $name;
    public $username;
    public $customer_id;
    public $phone;

    protected $rules = [
        'username' => 'required|email',
        'name' => 'required',
        'customer_id' => 'required',
    ];

    public function create()
    {
        $this->page_view = 'create-user';
    }

    public function edit($uuid)
    {
        try {
            /*TODO:: authorization check*/
            $user = User::findByUuid($uuid);
            if (!$user) {
                session()->flash('error', 'Customer not found');
            } else {

                $this->customer_id = $uuid;
                $this->user = $user;

                $this->page_view = 'edit-user';

            }
        } catch (\Exception $ex) {
            session()->flash('error', 'Something went wrong ');
        }


    }


    public function list()
    {
        $this->page_view = '';
        $this->reset();

    }


    public function render()
    {

        if ($this->page_view) {


            $customers = Customer::mine()->order()->get();


            if ($this->user) {
                $user = $this->user;

                $this->user_uuid = $user->uuid;
                $this->name = $user->name;
                $this->username = $user->username;
                $this->phone = $user->phone;
                $this->customer_id = $user->customer->uuid;

            }

            return view('livewire.' . $this->page_view, compact('customers'));
        }


        if (strlen($this->search) > 1) {
            $users = User::ui()->mine()->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('phone', 'like', '%' . $this->search . '%')
                ->orWhere('username', 'like', '%' . $this->search . '%')
                ->paginate();


        } else {

            $users = User::ui()->mine()->paginate();
        }


        return view('livewire.users', compact('users'));
    }


    public function new_user()
    {
        $this->validate();

        $customer = Customer::where('uuid', $this->customer_id)->mine()->firstOrFail();

        $password = str_shuffle(Str::password(10, true, true, false) . '@+');


        $user = User::create([
            'customer_id' => $customer->id,
            'name' => $this->name,
            'username' => $this->username,
            'phone' => $this->phone,
            'uuid' => uuid(),
            'password' => $password,
            'created_by' => user('id'),
            'type' => 'ui',
        ]);

        trail('user-created', 'User created successfully', $user);


        session()->flash('message','Portal user created. </br></br>Username: ' . $user->username . '</br></br>   Password: ' . $password . ' </br>');


        // session()->flash('message', 'User create successfully. Login details sent to: ' . $this->username);

        $this->list();

        //return redirect()->route('users');
    }


    public function update_user()
    {
        $this->validate();

        $user = User::findByUuid($this->user_uuid);

        $customer = Customer::where('uuid', $this->customer_id)->mine()->firstOrFail();

        $update = $user->update([
            'name' => $this->name,
            'phone' => $this->phone,
            'customer_id' => $customer->id,
            'username' => $this->username,
        ]);

        Log::info('update_user', ['update' => $update, 'this' => $this]);

        trail('user-updated', 'User updated successfully', $user);

        session()->flash('message', 'User updated successfully');

        return redirect()->route('users');
    }


}
