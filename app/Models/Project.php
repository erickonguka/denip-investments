<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'client_id',
        'category_id',
        'start_date',
        'end_date',
        'budget',
        'status',
        'progress',
        'media',
        'public_token',
        'is_public',
        'assigned_users',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'progress' => 'integer',
        'media' => 'array',
        'is_public' => 'boolean',
        'assigned_users' => 'array'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if ($project->is_public && !$project->public_token) {
                $project->public_token = \Str::random(32);
            }
            if (!$project->slug) {
                $project->slug = \Str::slug($project->title);
            }
        });
        
        static::updating(function ($project) {
            if ($project->isDirty('title') && !$project->isDirty('slug')) {
                $project->slug = \Str::slug($project->title);
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function assignedUsers()
    {
        if (!$this->assigned_users) {
            return collect();
        }
        return User::whereIn('id', $this->assigned_users)->get();
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}