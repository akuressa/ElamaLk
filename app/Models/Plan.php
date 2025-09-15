<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'plan_id',
        'plan_name',
        'plan_description',
        'plan_features',
        'plan_price',
        'plan_validity',
        'is_trial',
        'is_recommended',
        'is_customer_support',
        'is_private',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     *  
     */
    protected $casts = [
        'plan_features' => 'array', // Automatically cast JSON to array
        'is_trial' => 'integer',
        'is_recommended' => 'integer',
        'is_customer_support' => 'integer',
        'is_private' => 'integer',
        'is_active' => 'integer'
    ];
}
