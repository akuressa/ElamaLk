<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessService extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_service_id',
        'business_id',
        'business_category_id',
        'business_service_name',
        'business_service_slug',
        'business_service_description',
        'status',
    ];
}
