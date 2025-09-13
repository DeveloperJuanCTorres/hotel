<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'categorie_expense_id',
        'date',
        'description',
        'amount',
        'referencia',
        'pay_method_id',
        'boxe_opening_id'
    ];

    public function catexpense()
    {
        return $this->belongsTo(CategorieExpense::class, 'categorie_expense_id', 'id');
    }

    public function paymethod()
    {
        return $this->belongsTo(PayMethod::class, 'pay_method_id', 'id');
    }
}
