<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class IgvType extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class, "igv_type_id", "id");
    }
}
