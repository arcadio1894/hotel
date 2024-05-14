<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationPay extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'reservation_id',
        'pay',
        'date_pay'
    ];

    protected $dates = ['date_pay'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

}