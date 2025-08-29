<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Contact extends Model
{
    use HasFactory;

     protected $fillable = [
        'tipo_doc',
        'numero_doc',
        'name',
        'address',
        'email',
        'phone',
        'type',
    ];

    public function transaction()
    {
        return $this->hasMany(Transaction::class, "contact_id", "id");
    }
}
