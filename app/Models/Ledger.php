<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;
    protected $table = 'ledgers';
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class, 'proxy_customer_id');
    }

    public function end_customer()
    {
        return $this->belongsTo(Customer::class, 'initiating_customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
