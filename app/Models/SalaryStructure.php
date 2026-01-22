<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getGrossSalaryAttribute()
    {
        return $this->base_salary + 
               $this->housing_allowance + 
               $this->transport_allowance + 
               $this->other_allowances;
    }

    public function getNetSalaryAttribute()
    {
        return $this->gross_salary - $this->total_deductions;
    }

    public function getTotalDeductionsAttribute()
    {
        return ($this->pension_employee ?? 0) + ($this->tax_paye ?? 0);
    }
}
