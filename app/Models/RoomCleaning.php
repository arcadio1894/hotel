<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomCleaning extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'room_id',
        'date_start',
        'employer_id',
        'date_end',
    ];

    protected $dates = ['date_start', 'date_end'];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function employer()
    {
        return $this->hasMany(Employer::class);
    }

}
