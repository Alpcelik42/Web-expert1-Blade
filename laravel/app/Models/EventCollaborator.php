<?php
// app/Models/EventCollaborator.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventCollaborator extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'user_id', 'role'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isOwner()
    {
        return $this->role === 'owner';
    }

    public function isCoHost()
    {
        return $this->role === 'co_host';
    }
}
