<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
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
        'business_name',
        'business_description',
        'business_cover_image_url',
        'business_logo_url',
        'business_website_url',
        'business_email',
        'business_phone',
        'business_address',
        'business_country',
        'business_state',
        'business_city',
        'status',
    ];
    
}
