<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'security_question',
        'security_answer'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'security_answer'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteEvents()
    {
        return $this->belongsToMany(Event::class, 'favorites')
            ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(TicketBooking::class);
    }

    public function collaboratedEvents()
    {
        return $this->belongsToMany(Event::class, 'event_collaborators')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function upcomingEvents()
    {
        return $this->bookings()
            ->whereHas('event', function($query) {
                $query->where('start_date', '>', now());
            })
            ->with('event')
            ->get()
            ->pluck('event');
    }

    public function pastEvents()
    {
        return $this->bookings()
            ->whereHas('event', function($query) {
                $query->where('start_date', '<', now());
            })
            ->with('event')
            ->get()
            ->pluck('event');
    }

    public function verifySecurityAnswer($answer)
    {
        return strtolower(trim($this->security_answer)) === strtolower(trim($answer));
    }
}
