<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogView extends Model
{
    protected $fillable = ['blog_id', 'ip_address', 'viewed_at'];
    
    protected $casts = [
        'viewed_at' => 'datetime'
    ];
    
    public $timestamps = false;

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
}