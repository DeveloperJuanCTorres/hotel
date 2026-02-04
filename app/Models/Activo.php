<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Activo extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'room_id',
        'name',
        'description',
        'status',
        'active'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
