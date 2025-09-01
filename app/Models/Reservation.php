<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'room_id',
        'fecha_inicio',
        'hora_inicio',
        'fecha_fin',
        'hora_fin',
        'total',
        'saldo'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
}
