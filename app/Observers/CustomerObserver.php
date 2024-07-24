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

        $wallet = $customer->createWallet([
            'name' => config('wallet.wallet.default.name'),
            'slug' => config('wallet.wallet.default.slug')
        ]);

        //Auto generate api user for each customer
        User::create([
            'username' => 'api-'.bin2hex(random_bytes(5)),
            'api_secret' => Str::password(35, true, true, false),
            'type' => 'api',
            'uuid' => uuid(),
            'customer_id' => $customer->id
        ]);


    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        //
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
