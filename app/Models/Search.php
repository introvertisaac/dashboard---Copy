<?php

namespace App\Models;

use App\Jobs\LedgerLoggerJob;
use App\Traits\CustomerScopeTrait;
use App\Traits\ModelTrait;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Search extends Model
{
    use HasFactory, CustomerScopeTrait, ModelTrait;

    protected $table = 'searches';
    protected $guarded = [];

    public function getSearchTypeLabelAttribute()
    {
        return str_slug_reverse($this->search_type, '_');
    }

    public function getCostLabelAttribute()
    {
        return 'KES ' . number_format($this->cost, 2);
    }


    public function getLabelAttribute()
    {
        return optional($this->response)->name;
    }


    public function setMetaAttribute($value)
    {
        $this->attributes['meta'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setIprsResponseAttribute($value)
    {
        $this->attributes['iprs_response'] = is_array($value) ? json_encode($value) : $value;
    }

    public function setDlResponseAttribute($value)
    {
        $this->attributes['dl_response'] = is_array($value) ? json_encode($value) : $value;
    }

    public function getResponseAttribute($value)
    {
        return json_decode($value);
    }

    public function getMetaAttribute($value)
    {
        return json_decode($value);
    }

    public function getDlResponseAttribute($value)
    {
        return json_decode($value);
    }

    public function getIprsResponseAttribute($value)
    {
        return json_decode($value);
    }

    public static function newSearch($user, $type, $param, $meta = null, $channel = 'api')
    {
        $cost = config('billing.' . $type);
        $customer = $user->customer;
        $wallet = $customer->wallet;
        $wallet_balance = $wallet->balance;
        //$type_key = rtrim($type, "_check");
        $pos = strpos($type, "_check");
        $type_key = ($pos !== false) ? substr($type, 0, $pos) : $type;
        $cost_client = optional($customer->charges)->$type_key;
        $_wholesale = config('billing.' . $type . '_wholesale');

        if ($customer->status != Customer::ACTIVE) {
            return ['error' => ['message' => 'Customer Account Disabled', 'response_code' => 403]];
        }

        if (is_null($cost_client) || (intval($cost_client)<1)) {
            return ['error' => ['message' => 'API pricing not configured', 'response_code' => 403]];
        }
        $customer_charge = ($cost_client) ?: $_wholesale;

        try {

            $customer_withdraw = $wallet->withdraw($customer_charge, ['type' => 'customer_charge']);

        } catch (BalanceIsEmpty|InsufficientFunds $e) {
            return ['error' => ['message' => 'Low credit balance, kindly topup account', 'response_code' => 402]];
        } catch (\Throwable $e) {
            Log::critical('Wallet Error', [$wallet->id]);

            return ['error' => ['message' => 'Wallet Error', 'response_code' => 424]];
        }

        $balance_after = $wallet->balance;


        $search = Search::create([
            'search_param' => $param,
            'search_type' => $type,
            'meta' => $meta,
            'search_uuid' => Str::uuid(),
            'user_id' => $user->id,
            'transaction_id' => $customer_withdraw->id,
            'customer_id' => $customer->id,
            'channel' => $channel,
            'cost' => $customer_charge,
            'wholesale_cost' => $_wholesale,
            'balance_before' => $wallet_balance,
            'balance_after' => $balance_after,
        ]);

        $user->update([
            'last_active_at' => now()
        ]);

        LedgerLoggerJob::dispatch($search);

        return $search;
    }



    public static function oldSearch($user, $type, $param, $meta = null, $channel = 'api')
    {
        $cost = config('billing.' . $type);
        $customer = $user->customer;
        $wallet = $customer->wallet;
        $wallet_balance = $wallet->balance;
        //$type_key = rtrim($type, "_check");
        $pos = strpos($type, "_check");
        $type_key = ($pos !== false) ? substr($type, 0, $pos) : $type;
        $cost_client = optional($customer->charges)->$type_key;
        $parent = null;
        $parent_charge = 0;


        if (!is_null($customer->parent_customer_id)) {

            $parent = $customer->parent;


            $parent_balance = $parent->balance;
            $parent_charge_fetch = optional($parent->charges)->$type_key;
            $parent_charge = ($parent_charge_fetch) ?: $cost;

            if ($parent_balance < $parent_charge) {
                return null;
            }
        }


        $cost = ($cost_client) ?: $cost;


        $charge = config('billing.' . $type . '_wholesale');
        /*$float = Setting::where('key', 'float')->first()->value;*/
        //TODO:: implement global float
        $float = 10000;

        $user_id = $user->id;

        $user->update([
            'last_active_at' => now()
        ]);

        if ($wallet_balance >= $cost && ($float > 0)) {

            $balance_before = $wallet->balance;
            $withdrawal = $wallet->withdraw($cost, ['type' => 'customer_charge']);
            $balance_after = $wallet->balance;

            if ($withdrawal) {

                if ($parent_charge === 0) {

                } else {
                    $parent_wallet = $parent->wallet;

                    //exemption for admin where own wallet is same as parent wallet
                    if ($parent_wallet->id != $wallet->id) {
                        $parent_withdrawal = $parent_wallet->withdraw($parent_charge, ['type' => 'parent_charge']);
                    }


                }

                //Setting::where('key', 'float')->decrement('value', $charge);
                //TODO:: implement global float decrement

                return Search::create([
                    'search_param' => $param,
                    'search_type' => $type,
                    'meta' => $meta,
                    'search_uuid' => Str::uuid(),
                    'user_id' => $user_id,
                    'transaction_id' => $withdrawal->id,
                    'customer_id' => $customer->id,
                    'cost' => $cost,
                    'wholesale_cost' => $charge,
                    'balance_before' => $balance_before,
                    'balance_after' => $balance_after,
                ]);


            } else {
                return null;
            }

        }

        return null;
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }



    public function log_ledgers()
    {
        $batch = uuid();

        $search = $this;
        $type_key = $search->search_type;
        $_wholesale = config('billing.' . $type_key . '_wholesale');

        $customer = $search->customer;

        $dated = $search->created_at;
        $transaction = $search->transaction;

        $buying_price = abs($transaction->amount);
        $selling_price = $buying_price;

        //$channel = $search->channel;
        $channel = $search->user->type === 'api' ? 'api' : 'portal';


        $margin = $selling_price - $buying_price;
        Ledger::create([
            'service' => $search->search_type,
            'customer_id' => $customer->id,
            'initiating_customer_id' => $customer->id,
            'proxy_customer_id' => $customer->id,
            'user_id' => $search->user_id,
            'wallet_id' => $transaction->wallet_id,
            'class' => 'E',
            'channel' => $channel,
            'search_id' => $search->id,
            'transaction_id' => $search->transaction_id,
            'batch' => $batch,
            'buying_price' => $buying_price,
            'selling_price' => $selling_price,
            'margin' => $margin,
            'dated' => $dated,
        ]);
        $selling_prices[] = $selling_price;
        $buying_prices[] = $buying_price;
        $proxy_customers[] = $customer->id;

        $parent_levels = $customer->parent_levels;
        foreach ($parent_levels as $parent_level) {

            $level_parent_id = optional($parent_level)->id;
            $level_parent_wallet_id = optional($parent_level)->wallet_id;
            $level_parent = Customer::find($level_parent_id);

            if ($level_parent) {

                $level_cost = optional(optional($level_parent)->charges)->$type_key;
                $selling_price = ($level_cost) ?: $_wholesale;
                $selling_price = intval($selling_price);

                $sold_price = end($selling_prices);
                $selling_prices[] = $level_cost;

                $proxy_customer_id = end($proxy_customers);
                $proxy_customers[] = $level_parent->id;


                $parent_wallet = Wallet::find($level_parent_wallet_id);
               // $buying_price = is_null($level_parent) || (is_null(optional($level_parent)->parent)) ? 0 : optional($level_parent->parent->charges)->$type_key;
                $buying_price = is_null($level_parent) || (is_null(optional($level_parent)->parent)) ? 0 : optional($level_parent->charges)->$type_key;
                $margin = $sold_price - $buying_price;

                try {
                    $parent_withdrawal = $parent_wallet->withdraw($selling_price, ['type' => 'parent_charge']);
                } catch (BalanceIsEmpty|InsufficientFunds $e) {

                    /*TODO:: lock child parent accounts*/

                    $parent_withdrawal = $parent_wallet->forceWithdraw($selling_price, ['type' => 'parent_charge']);

                    Log::critical('Parent Wallet is low on funds', ['wallet_id' => optional($parent_wallet)->id, 'text' => $e->getMessage()]);

                    //return 'Parent Wallet is low on funds';
                } catch (\Throwable $e) {
                    Log::critical('Wallet Error', ['wallet_id' => optional($parent_wallet)->id, 'text' => $e->getMessage()]);
                }

                if (isset($parent_withdrawal)) {
                    $channel = $search->user->type === 'api' ? 'api' : 'portal';
                    Ledger::create([
                        'service' => $search->search_type,
                        'customer_id' => $level_parent->id,
                        'initiating_customer_id' => $customer->id,
                        'proxy_customer_id' => $proxy_customer_id,
                        'user_id' => $search->user_id,
                        'wallet_id' => $level_parent_wallet_id,
                        'class' => 'R',
                        'channel' => $channel,
                        'search_id' => $search->id,
                        'transaction_id' => $parent_withdrawal->id,
                        'batch' => $batch,
                        'buying_price' => $buying_price,
                        'selling_price' => $sold_price,
                        'margin' => $margin,
                        'dated' => $dated,
                    ]);
                }

                unset($parent_withdrawal);


            }

        }

        $search->update([
            'ledgered_at' => now()
        ]);

    }

    public function getIsOlderThan24HoursAttribute()
    {
        return $this->created_at->lt(Carbon::now()->subDay());
    }


}
