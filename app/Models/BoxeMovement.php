<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class BoxeMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxe_opening_id',
        'type',
        'description',
        'amount',
        'date',
        'pay_method_id'
    ];
}
