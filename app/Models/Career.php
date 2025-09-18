<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'location',
        'type',
        'description',
        'requirements',
        'benefits',
        'salary_min',
        'salary_max',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2'
    ];
    
    public function applications()
    {
        return $this->hasMany(CareerApplication::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($career) {
            if (!$career->slug) {
                $career->slug = \Str::slug($career->title);
            }
        });
        
        static::updating(function ($career) {
            if ($career->isDirty('title') && !$career->isDirty('slug')) {
                $career->slug = \Str::slug($career->title);
            }
        });
    }
}
