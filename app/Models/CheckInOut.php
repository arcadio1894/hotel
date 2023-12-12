<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckInOut extends Model
{
    protected $fillable = ['reservation_id', 'checkin_date', 'checkout_date'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
