<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image',
        'created_at',
        'updated_at',
    ];

    // Get user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Get blog tags
    public function blogTags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_id', 'blog_id');
    }

    // Get blog categories
    public function blogCategories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_id', 'blog_category_id');
    }
}
