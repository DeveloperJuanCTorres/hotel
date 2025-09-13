<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CategorieExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, "categorie_expense_id", "id");
    }
}
