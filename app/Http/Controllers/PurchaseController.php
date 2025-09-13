<?php

namespace App\Http\Controllers;

use App\Models\BoxeMovement;
use App\Models\BoxeOpening;
use App\Models\Contact;
use App\Models\DetailPurchase;
use App\Models\PayMethod;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('purchases.index', compact('purchases','caja_abierta'));
    }

    public function create()
    {
        $products = Product::all();
        $proveedores = Contact::where('type','PROVEEDOR')->get();
        $categories = Taxonomy::all();
        $pay_methods = PayMethod::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('purchases.create', compact('products','caja_abierta','categories','pay_methods','proveedores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:contacts,id',
            'referencia' => 'nullable|string',
            'date' => 'required|date',
            'pay_method_id' => 'required|exists:pay_methods,id',
            'status' => 'required|string',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:products,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.importe' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();                      

            $compra = Purchase::create([
                'contact_id'      => $request->proveedor_id,
                'boxe_opening_id' => $request->boxe_opening_id,
                'pay_method_id'   => $request->pay_method_id,
                'referencia'  => $request->referencia,
                'total'           => array_sum(array_column($request->productos, 'importe')),
                'status' => $request->status,
                'date' => $request->date,
            ]);

            foreach ($request->productos as $prod) {
                $product = Product::findOrFail($prod['id']);
                
                $product->increment('stock', $prod['cantidad']);

                DetailPurchase::create([
                    'purchase_id'        => $compra->id,
                    'product_id'     => $prod['id'],
                    'cantidad'       => $prod['cantidad'],
                    'precio_unitario'=> $prod['precio'],
                    'subtotal'       => $prod['importe'],
                ]);
            }

            BoxeMovement::create([
                'boxe_opening_id' => $request->boxe_opening_id,
                'type'            => 'egreso',
                'amount'          => $compra->total,
                'date'            => now(),
                'pay_method_id'   => $request->pay_method_id,
            ]);

            DB::commit();

            return redirect()->route('purchases')->with('success', 'Compra registrada con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la compra: ' . $e->getMessage());
        }
    }
}
