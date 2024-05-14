<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'customer_id',
        'employer_id',
        'status_id',
        'paymethod_id',
        'start_date',
        'end_date',
        'initial_pay',
        'total_guest',
        'code',
        'document_type',
        'document',
        'name',
        'lastname',
        'phone',
        'email',
        'birth',
        'address',
        'status_id',
        "total_pay",
        "state_paid"
    ];

    protected $dates = ['start_date', 'end_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function employer()
    {
        return $this->belongsTo(Employer::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function paymethod()
    {
        return $this->belongsTo(Paymethod::class);
    }

    public function details()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function checkInOuts()
    {
        return $this->hasMany(CheckInOut::class);
    }

}
