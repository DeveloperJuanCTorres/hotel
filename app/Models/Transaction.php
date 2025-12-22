<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'contact_id',
        'cant_personas',
        'precio_unitario',
        'cant_noches',
        'total',
        'estado_pago',
        'pay_method_id',
        'fecha_entrada',
        'fecha_salida',
        'hora_salida',
        'status',
        'boxe_opening_id',
        'ref_nro'
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
