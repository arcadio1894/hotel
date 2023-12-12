<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'document_type',
        'document',
        'name',
        'lastname',
        'phone',
        'email',
        'birth',
        'address',

    ];
    protected $attributes = [
        'lastname' => null, // Establecer lastname como nullable
    ];

    protected $casts = [
        'lastname' => 'string', // Asegurarse de que se caste a string
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
