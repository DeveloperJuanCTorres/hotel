<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BoxeClosure extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxe_opening_id',
        'monto_inicial',
        'total_ingresos',
        'total_egresos',
        'balance_final',
        'detalle',
        'fecha_cierre',
    ];

    protected $casts = [
        'detalle' => 'array',
        'fecha_cierre' => 'datetime',
    ];

    public function boxeOpening()
    {
        return $this->belongsTo(BoxeOpening::class, 'boxe_opening_id');
    }
}
