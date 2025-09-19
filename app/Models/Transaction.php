<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'payment_id',
        'user_id',
        'plan_id',
        'business_plan_subscription_id',
        'description',
        'payment_gateway_name',
        'transaction_currency',
        'transaction_total',
        'transaction_date',
        'invoice_number',
        'invoice_prefix',
        'invoice_details',
        'transaction_status',
        'status'
    ];

    protected $casts = [
        'transaction_total' => 'decimal:2',
        'invoice_details' => 'array',
        'transaction_date' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'plan_id');
    }

    public function businessPlanSubscription()
    {
        return $this->belongsTo(BusinessPlanSubscription::class, 'business_plan_subscription_id', 'id');
    }
}
