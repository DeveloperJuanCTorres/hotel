<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'taxonomy_id',
        'unit_id',
        'description',
        'price',
        'stock',
        'image',
        'igv_type_id',
        'price_compra'
    ];

    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function igvtype()
    {
        return $this->belongsTo(IgvType::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(DetailPurchase::class, 'product_id');
    }
}
