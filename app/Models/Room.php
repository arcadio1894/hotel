<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
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
}
