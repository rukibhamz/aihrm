<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(FinancialRequestCategory::class, 'category_id');
    }

    public function managerApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_manager');
    }

    public function financeApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_finance');
    }
}
