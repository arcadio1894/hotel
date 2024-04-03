<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomOut extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'room_id',
        'date_start',
        'date_end',
    ];

    protected $dates = ['date_start', 'date_end'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
