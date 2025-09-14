<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PayMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, "categorie_expense_id", "id");
    }
    
}
