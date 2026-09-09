<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'boxe_opening_id',
        'pay_method_id',
        'referencia',
        'total',
        'status',
        'date'
    ];

    // Proveedor
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    // Método de pago
    public function payMethod()
    {
        return $this->belongsTo(PayMethod::class, 'pay_method_id', 'id');
    }

    // Detalle de la compra
    public function details()
    {
        return $this->hasMany(DetailPurchase::class, 'purchase_id');
    }
}