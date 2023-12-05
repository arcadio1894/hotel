<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'document_type',
        'document',
        'name',
        'lastname',
        'phone',
        'email',
        'birth',
        'address'
    ];

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
}
