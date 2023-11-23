<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use HasFactory, SoftDeletes;
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'name',
        'lastname',
        'position',
        'dni',
        'address',
        'birth',
        'phone'
    ];
}
