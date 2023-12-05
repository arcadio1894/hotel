<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'room_type_id',
        'level',
        'number',
        'status',
        'description',
        'image'
    ];
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function details()
    {
        return $this->hasMany(ReservationDetail::class);
    }

}
