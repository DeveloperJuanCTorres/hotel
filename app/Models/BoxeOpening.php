<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BoxeOpening extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxe_id',
        'user_id',
        'monto_inicial',
        'fecha_apertura',
        'monto_final',
        'fecha_cierre',
        'status',
    ];

    public function box()
    {
        return $this->belongsTo(Box::class, 'boxe_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function closures()
    {
        return $this->hasMany(BoxeClosure::class, 'boxe_opening_id');
    }
}
