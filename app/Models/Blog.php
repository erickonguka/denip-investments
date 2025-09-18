<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image',
        'meta_keywords', 'meta_description', 'category_id', 'author_id',
        'status', 'published_at', 'views'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'published_at' => 'datetime',
        'views' => 'integer'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($blog) {
            if (empty($blog->slug)) {
                $blog->slug = Str::slug($blog->title);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(BlogComment::class)->whereNull('parent_id');
    }

    public function allComments()
    {
        return $this->hasMany(BlogComment::class);
    }

    public function approvedComments()
    {
        return $this->hasMany(BlogComment::class)->where('status', 'approved');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function incrementViews($ipAddress = null)
    {
        if (!$ipAddress) {
            $ipAddress = request()->ip();
        }
        
        // Check if this IP has already viewed this blog
        $existingView = \App\Models\BlogView::where('blog_id', $this->id)
            ->where('ip_address', $ipAddress)
            ->first();
            
        if (!$existingView) {
            \App\Models\BlogView::create([
                'blog_id' => $this->id,
                'ip_address' => $ipAddress,
                'viewed_at' => now()
            ]);
            
            $this->increment('views');
        }
    }
    
    public function views()
    {
        return $this->hasMany(\App\Models\BlogView::class);
    }
}