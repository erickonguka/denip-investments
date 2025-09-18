<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'recipient_id',
        'subject',
        'body',
        'read_at',
        'parent_id',
        'reply_to_id',
        'attachments',
        'documents',
        'sender_name',
        'sender_email'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachments' => 'array',
        'documents' => 'array'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function parent()
    {
        return $this->belongsTo(Message::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Message::class, 'parent_id');
    }
    
    public function replyTo()
    {
        return $this->belongsTo(Message::class, 'reply_to_id');
    }
}