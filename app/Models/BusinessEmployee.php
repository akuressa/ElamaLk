<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessEmployee extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_id',
        'business_employee_id',
        'business_employee_name',
        'business_employee_email',
        'business_employee_phone',
        'permissions',
        'status',
        'is_login'
    ];
}
