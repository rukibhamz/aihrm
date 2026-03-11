<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRelief extends Model
{
    protected $fillable = ['name', 'type', 'amount', 'description', 'is_active'];

    public function employees()
    {
        return $this->belongsToMany(User::class, 'employee_tax_reliefs')->withPivot('is_active')->withTimestamps();
    }
}
