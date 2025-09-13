<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DetailPurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'cantidad',
        'precio_unitario',
        'subtotal'
    ];
}
