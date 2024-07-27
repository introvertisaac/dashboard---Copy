<?php

namespace App\Models;

use App\Traits\CustomerScopeTrait;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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


}
