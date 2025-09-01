<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Room extends Model
{
    use HasFactory;

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, "room_id", "id");
    }

    public function reservation()
    {
        return $this->hasMany(Reservation::class, "room_id", "id");
    }
}
