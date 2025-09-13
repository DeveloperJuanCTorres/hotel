<?php

namespace App\Http\Controllers;

use App\Models\BoxeMovement;
use App\Models\Contact;
use App\Models\DetailSale;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    // public function store(Request $request)
    // {
    //     $transaction_id = null;
        
    //     $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
    //     if (!$cliente) {
    //         $cliente = new Contact();
    //         $cliente->tipo_doc = $request->tipo_doc;
    //         $cliente->numero_doc = $request->numero_doc;
    //         $cliente->name = $request->client;
    //         $cliente->address = $request->address;
    //         $cliente->save();
    //     }

    //     if ($request->tipo_cliente == 1) {
    //         $transaction_id = $request->transaction_id;
    //     }
        
    //     $venta = Sale::create([
    //         'contact_id' => $cliente->id,
    //         'transaction_id' => $transaction_id,
    //         'status' => 'pagado',
    //         'total' => array_sum(array_column($request->productos, 'importe')),
    //         'boxe_opening_id' => $request->boxe_opening_id,
    //         'pay_method_id' => $request->tipo_comprobante
    //     ]);

        
    //     foreach ($request->productos as $prod) {
    //         $product = Product::find($prod['id']);
    //         if ($product) {
    //             $product->decrement('stock', $prod['cantidad']);
    //         }
    //         $product->save();
    //         $detail = DetailSale::create([
    //             'sale_id' => $venta->id,
    //             'product_id' => $prod['id'],
    //             'cantidad' => $prod['cantidad'],
    //             'precio_unitario' => $prod['precio'],
    //             'subtotal' => $prod['importe'],
    //         ]);
    //     }

    //     $boxe_movement = BoxeMovement::create([
    //         'boxe_opening_id' =>$request->boxe_opening_id,
    //         'type' =>'ingreso',
    //         'amount' =>$venta->total,
    //         'date' =>now(),
    //         'pay_metohd_id' =>$request->tipo_comprobante,
    //     ]);

    //     return redirect()->route('pos')->with('success', 'Venta registrada con éxito.');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'tipo_doc' => 'nullable|string',
            'numero_doc' => 'nullable|string',
            'client' => 'nullable|string',
            'address' => 'nullable|string',
            'tipo_cliente' => 'required|in:1,2',
            'transaction_id' => 'nullable|exists:transactions,id',
            'boxe_opening_id' => 'required|exists:boxe_openings,id',
            'tipo_comprobante' => 'required|exists:pay_methods,id',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:products,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio' => 'required|numeric|min:0',
            'productos.*.importe' => 'required|numeric|min:0',
        ]);

        try {
            $cliente_id = "";
            DB::beginTransaction();

            $transaction_id = $request->tipo_cliente == 1 ? $request->transaction_id : null;

            if ($transaction_id != null) {

                $transaction = Transaction::findorfail($transaction_id);
                $cliente_id = $transaction->contact_id;
            }
            else{
                $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
                if (!$cliente) {
                    $cliente = Contact::create([
                        'tipo_doc'   => $request->tipo_doc,
                        'numero_doc' => $request->numero_doc,
                        'name'       => $request->client,
                        'address'    => $request->address,
                    ]);
                }
                $cliente_id = $cliente->id;
            }            

            $venta = Sale::create([
                'contact_id'      => $cliente_id,
                'transaction_id'  => $transaction_id,
                'status'          => 'pagado',
                'total'           => array_sum(array_column($request->productos, 'importe')),
                'boxe_opening_id' => $request->boxe_opening_id,
                'pay_method_id'   => $request->tipo_comprobante,
            ]);

            foreach ($request->productos as $prod) {
                $product = Product::findOrFail($prod['id']);

                if ($product->stock < $prod['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto {$product->name}");
                }

                $product->decrement('stock', $prod['cantidad']);

                DetailSale::create([
                    'sale_id'        => $venta->id,
                    'product_id'     => $prod['id'],
                    'cantidad'       => $prod['cantidad'],
                    'precio_unitario'=> $prod['precio'],
                    'subtotal'       => $prod['importe'],
                ]);
            }

            BoxeMovement::create([
                'boxe_opening_id' => $request->boxe_opening_id,
                'type'            => 'ingreso',
                'amount'          => $venta->total,
                'date'            => now(),
                'pay_method_id'   => $request->tipo_comprobante, // corregido
            ]);

            DB::commit();

            return redirect()->route('pos')->with('success', 'Venta registrada con éxito.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }
}
