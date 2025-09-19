<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BusinessPlanSubscription extends Model
{
    protected $fillable = [
        'subscription_id',
        'user_id',
        'business_id',
        'business_plan_id',
        'subscription_price',
        'duration_months',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'subscription_price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'business_id');
    }

    public function businessPlan()
    {
        return $this->belongsTo(BusinessPlan::class, 'business_plan_id', 'business_plan_id');
    }

    // Helper methods
    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isActive()
    {
        return $this->status === 'active' && $this->end_date > Carbon::now();
    }

    public function isExpired()
    {
        return $this->end_date <= Carbon::now();
    }

    public function getRemainingDays()
    {
        if ($this->isExpired()) {
            return 0;
        }
        return (int) Carbon::now()->diffInDays($this->end_date, false);
    }

    public function getIncludedServices()
    {
        if (!$this->businessPlan) {
            return collect();
        }
        
        return $this->businessPlan->getServices();
    }
}
