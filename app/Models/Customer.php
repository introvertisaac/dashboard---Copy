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
            $this->attributes['charges'] = json_decode(json_encode($value), true);
        } else {
            $this->attributes['charges'] = is_array($value) ? json_encode($value) : $value;
        }
    }

    public function getChargesAttribute($value)
    {
        return json_decode($value);
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


}
