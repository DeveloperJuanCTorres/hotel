<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\Invoice;
use App\Models\PayMethod;
use App\Models\Product;
use App\Models\Taxonomy;
use App\Models\Transaction;
use Illuminate\Http\Request;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $categories = Taxonomy::all();
        $transactions = Transaction::where('status', 0)->get();
        $pay_methods = PayMethod::all();
        $invoices = Invoice::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('pos.index', compact('products','caja_abierta','categories','transactions','pay_methods','invoices'));
    }
}
