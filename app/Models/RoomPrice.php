<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_type_id',
        'season_id',
        'duration_hours',
        'price',
    ];

    public function room_type()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
}
