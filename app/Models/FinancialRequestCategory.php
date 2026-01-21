<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRequestCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function requests()
    {
        return $this->hasMany(FinancialRequest::class, 'category_id');
    }
}
