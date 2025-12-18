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

    public function paymethod()
    {
        return $this->belongsTo(PayMethod::class, 'pay_method_id', 'id');
    }

    public function details()
    {
        return $this->hasMany(DetailPurchase::class, 'purchase_id');
    }
}
