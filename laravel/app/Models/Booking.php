<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'quantity',
        'unit_price',
        'total_price',
        'booking_number',
        'status',
        'payment_status',
        'notes'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    // Generate een uniek bookingnummer
    public static function generateBookingNumber()
    {
        do {
            $number = 'BK-' . strtoupper(substr(md5(uniqid()), 0, 8)) . '-' . date('Ymd');
        } while (self::where('booking_number', $number)->exists());

        return $number;
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Accessors
    public function getFormattedTotalPriceAttribute()
    {
        return '€' . number_format($this->total_price, 2, ',', '.');
    }

    public function getFormattedUnitPriceAttribute()
    {
        return '€' . number_format($this->unit_price, 2, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'confirmed' => 'badge bg-success',
            'cancelled' => 'badge bg-danger',
            'completed' => 'badge bg-info'
        ];

        $texts = [
            'pending' => 'In afwachting',
            'confirmed' => 'Bevestigd',
            'cancelled' => 'Geannuleerd',
            'completed' => 'Voltooid'
        ];

        return '<span class="' . ($badges[$this->status] ?? 'badge bg-secondary') . '">' .
            ($texts[$this->status] ?? ucfirst($this->status)) . '</span>';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'badge bg-warning',
            'paid' => 'badge bg-success',
            'failed' => 'badge bg-danger',
            'refunded' => 'badge bg-info'
        ];

        $texts = [
            'pending' => 'Betaling in afwachting',
            'paid' => 'Betaald',
            'failed' => 'Betaling mislukt',
            'refunded' => 'Terugbetaald'
        ];

        return '<span class="' . ($badges[$this->payment_status] ?? 'badge bg-secondary') . '">' .
            ($texts[$this->payment_status] ?? ucfirst($this->payment_status)) . '</span>';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Helper methods
    public function canBeCancelled()
    {
        return $this->status === 'pending' || $this->status === 'confirmed';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }
}
