<?php

namespace App\Observers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Str;
use function Laravel\Prompts\password;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        //
        $customer->generateParentLevels();

        $wallet = $customer->createWallet([
            'name' => config('wallet.wallet.default.name'),
            'slug' => config('wallet.wallet.default.slug')
        ]);

        //Auto generate api user for each customer
        User::create([
            'username' => 'api-' . bin2hex(random_bytes(5)),
            'api_secret' => Str::password(35, true, true, false),
            'type' => 'api',
            'uuid' => uuid(),
            'customer_id' => $customer->id
        ]);


        //$wallet->deposit(config('billing.free_wallet_amount'));


    }

    /**
     * Handle the Customer "creating" event.
     */
    public function creating(Customer $customer): void
    {
        //
        $customer->api_count = $this->compute_api_count($customer);


    }

    /**
     * Handle the Customer "updating" event.
     */
    public function updating(Customer $customer): void
    {
        //
        $customer->api_count = $this->compute_api_count($customer);

    }


    public function compute_api_count($customer)
    {
        $charges = collect($customer->charges);

        $filtered = $charges->filter(function ($value, $key) {
            return !is_null($value) && intval($value) !== 0;
        });

        $list = $filtered->keys()->all();

        return count($list);
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        //

        if ($customer->wasChanged('status') && $customer->status === 'suspended') {
            $children = $customer->children()->get();
            foreach ($children as $child) {
                $child->update(['status' => Customer::SUSPENDED]);
            }

        }


    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
