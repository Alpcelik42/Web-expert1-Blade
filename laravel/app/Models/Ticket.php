<?php
// app/Models/Ticket.php (aanpassing)

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'type',
        'description',
        'price',
        'quantity_available',
        'quantity_sold',
        'max_per_user',
        'sale_start_date',
        'sale_end_date'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_start_date' => 'datetime',
        'sale_end_date' => 'datetime'
    ];

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Accessors
    public function getAvailableQuantityAttribute()
    {
        return $this->quantity_available - $this->quantity_sold;
    }

    public function getFormattedPriceAttribute()
    {
        return '€' . number_format($this->price, 2, ',', '.');
    }

    public function getIsAvailableAttribute()
    {
        if ($this->available_quantity <= 0) {
            return false;
        }

        $now = now();
        if ($this->sale_start_date && $now->lt($this->sale_start_date)) {
            return false;
        }

        if ($this->sale_end_date && $now->gt($this->sale_end_date)) {
            return false;
        }

        return true;
    }

    // Method om tickets te verkopen
    public function sellTickets($quantity)
    {
        if ($this->available_quantity < $quantity) {
            throw new \Exception('Niet genoeg tickets beschikbaar');
        }

        $this->increment('quantity_sold', $quantity);
        $this->save();
    }

    // Method om tickets terug te geven (bij annulering)
    public function returnTickets($quantity)
    {
        $this->decrement('quantity_sold', $quantity);
        $this->save();
    }
}
