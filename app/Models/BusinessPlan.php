<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_plan_id',
        'business_id',
        'business_service_ids',
        'plan_name',
        'plan_description',
        'plan_price',
        'duration_months',
        'duration_label',
        'is_active'
    ];

    protected $casts = [
        'plan_price' => 'decimal:2',
        'is_active' => 'boolean',
        'business_service_ids' => 'array'
    ];

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id', 'business_id');
    }

    public function businessServices()
    {
        return $this->belongsToMany(BusinessService::class, null, 'business_plan_id', 'business_service_id')
            ->whereIn('business_service_id', $this->business_service_ids ?? []);
    }

    // Helper method to get services
    public function getServices()
    {
        if (empty($this->business_service_ids)) {
            return collect();
        }
        
        return BusinessService::whereIn('business_service_id', $this->business_service_ids)->get();
    }
}
