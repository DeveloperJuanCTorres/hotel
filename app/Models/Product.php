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
    ];

    public function taxonomy()
    {
        return $this->belongsTo(Taxonomy::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}
