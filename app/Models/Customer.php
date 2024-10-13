<?php

namespace App\Models;

use App\Observers\CustomerObserver;
use App\Traits\ModelTrait;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Models\Transaction;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWallets;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[ObservedBy([CustomerObserver::class])]
class Customer extends Model implements Wallet
{
    use HasFactory, HasWallet, HasWallets, ModelTrait, SoftDeletes;

    protected $table = 'customers';

    protected $guarded = [];

    public const DISABLED = 'disabled';
    public const SUSPENDED = 'suspended';
    public const ACTIVE = 'active';

    public function getTotalDepositsAttribute()
    {
        return Transaction::select('amount')->where('payable_id', $this->id)->where('type', 'deposit')
            ->where('payable_type', get_class($this))->sum('amount');
    }

    public function getTotalWithdrawalsAttribute()
    {
        $sum = Transaction::select('amount')->where('payable_id', $this->id)->where('type', 'withdraw')
            ->where('payable_type', get_class($this))->sum('amount');

        return $sum;
    }

    public function setChargesAttribute($value)
    {
        if (is_object($value)) {
            $charges = json_decode(json_encode($value), true);
        } else {
            $charges = is_array($value) ? json_encode($value) : $value;
        }

        if (isset($charges) && is_array($charges)) {
            $charges = collect($charges)
                ->filter(function ($value) {
                    return intval($value) > 0;
                })
                ->toArray();
        }

        $this->attributes['charges'] = $charges;
    }

    public function getChargesAttribute($value)
    {
        return is_string($value) ? json_decode($value) : $value;
    }

    public function getParentLevelsAttribute($value)
    {
        return is_string($value) ? json_decode($value) : [];
    }


    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function getRouteKey()
    {
        return $this->uuid;
    }

    public function parent()
    {
        return $this->belongsTo(Customer::class, 'parent_customer_id');
    }

    public function children()
    {
        return $this->hasMany(Customer::class, 'parent_customer_id');
    }


    public function childAccounts()
    {
        return $this->children();
    }

    public function allChildAccounts()
    {
        return $this->childAccounts()->with('allChildAccounts');
    }

    // Get count of child accounts
    public function getChildAccountsCountAttribute()
    {
        return $this->childAccounts->count();
    }

    public static function findByUuid($uuid)
    {
        return Customer::where('uuid', $uuid)->first();
    }

    public function scopeMine($query)
    {
        if (!user()->is_super_admin) {
            $query->where('parent_customer_id', session('customer_id'));
        }

    }

    function scopeOrder($query)
    {
        $query->orderBy('name', 'asc');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function apiuser()
    {
        return $this->users()->where('type', 'api')->latest()->first();

    }

    public function getApiListAttribute()
    {
        $charges = collect($this->charges);

        $filtered = $charges->filter(function ($value, $key) {
            return !is_null($value) && $value !== 0;
        });

        return $filtered->keys()->all();
    }

    public function getAllowedServicesAttribute()
    {
        $service_details = config('billing.services');
        $customer_service_list = \customer()->api_list;
        return retainArrayElementsByKeys($service_details, $customer_service_list);
    }


    public function getBalanceLabelAttribute()
    {
        return 'KES ' . number_format($this->balance);
    }

    public function generateParentLevels()
    {
        $parents = [];
        $customer = $this;
        while ($customer && $customer->parent) {
            $customer = $customer->parent;
            $parents[] = [
                'id' => $customer->id,
                'name' => $customer->name,
                'wallet_id' => $customer->wallet->id
            ];
        }

        $this->update([
            'parent_levels' => $parents
        ]);

    }


}
