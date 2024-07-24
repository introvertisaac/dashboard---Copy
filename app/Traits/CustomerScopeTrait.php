<?php
namespace App\Traits;



trait CustomerScopeTrait
{
    public function scopeCurrentCustomer($query)
    {
        return $query->where('customer_id', customer_id());
    }

}
