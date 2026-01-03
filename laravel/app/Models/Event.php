<?php
// app/Models/Event.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'price',
        'capacity',
        'ticket_sale_start',
        'is_public',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'ticket_sale_start' => 'datetime',
        'price' => 'decimal:2',
        'is_public' => 'boolean'
    ];

    // Voor Blade: gebruik getMainImageAttribute()
    public function getMainImageAttribute()
    {
        // Simpele versie zonder null coalescing operator
        $mainImage = $this->images()->where('is_main', true)->first();

        if (!$mainImage) {
            $mainImage = $this->images()->first();
        }

        return $mainImage;
    }


    // Relationship voor eager loading
    public function mainImageRelation()
    {
        return $this->hasOne(EventImage::class)->where('is_main', true);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function isFavoritedByUser($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->favorites()->where('user_id', $userId)->exists();
    }

    public function collaborators()
    {
        return $this->hasMany(EventCollaborator::class);
    }

    public function isCollaborator($userId)
    {
        if (!$userId) {
            return false;
        }

        return $this->collaborators()->where('user_id', $userId)->exists();
    }

    public function canUserEdit($userId = null)
    {
        if (!$userId && Auth::check()) {
            $userId = Auth::id();
        }

        if (!$userId) {
            return false;
        }

        return $this->user_id == $userId ||
            $this->collaborators()
                ->where('user_id', $userId)
                ->whereIn('role', ['owner', 'co_host'])
                ->exists();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getTotalTicketsSoldAttribute()
    {
        return $this->tickets->sum('quantity_sold');
    }

    public function getIsSoldOutAttribute()
    {
        if (!$this->capacity) {
            return false;
        }

        return $this->total_tickets_sold >= $this->capacity;
    }

    public function getTicketSaleStartedAttribute()
    {
        return !$this->ticket_sale_start || $this->ticket_sale_start->isPast();
    }

    // Helper methods
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function getAvailableTicketsAttribute()
    {
        if (!$this->capacity) {
            return null;
        }

        return $this->capacity - $this->total_tickets_sold;
    }

    public function getFormattedPriceAttribute()
    {
        if (!$this->price || $this->price == 0) {
            return 'Gratis';
        }

        return '€' . number_format($this->price, 2, ',', '.');
    }
}
