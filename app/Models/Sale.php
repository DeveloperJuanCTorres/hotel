<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'transaction_id',
        'type',
        'status',
        'total',
        'boxe_opening_id',
        'pay_method_id'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function details()
    {
        return $this->hasMany(DetailSale::class, 'sale_id');
    }
}
