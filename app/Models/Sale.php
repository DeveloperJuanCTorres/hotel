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
}
