<?php

namespace App\Traits;

trait ModelTrait
{


    public function scopeActive($query)
    {
        $query->where('status', 'active');
    }

    public function scopeEnabled($query)
    {
        $query->where('enabled', true);
    }

    public function scopeStatus($query, $status)
    {
        $query->where('status', $status);
    }

    public function scopeSort($query)
    {
        $query->orderBy('sort', 'ASC');
    }


    public function scopeAlphabet($query, $by = 'name')
    {
        $query->orderBy($by, 'ASC');
    }


    public function getCreatedAtHumanAttribute()
    {
        return carbon($this->created_at)->format('M j, Y g:i A');
    }


    public function getUpdatedAtHumanAttribute()
    {
        return carbon($this->updated_at)->format('M j, Y g:i A');
    }


    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }



}
