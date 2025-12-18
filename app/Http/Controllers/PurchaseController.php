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

    public function list()
    {
        $purchases = Purchase::all();
        return view('purchases.partials.list', compact('purchases'));
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

    public function edit($id)
    {
        $products = Product::all();
        $purchase = Purchase::with('details.product')->findOrFail($id);
        $categories = Taxonomy::all();
        $proveedores = Contact::where('type','PROVEEDOR')->get();
        $pay_methods = PayMethod::all();

        $caja_abierta = BoxeOpening::where('status', 'abierta')->first();

        return view('purchases.edit', compact(
            'purchase',
            'products',
            'categories',
            'proveedores',
            'pay_methods',
            'caja_abierta'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:contacts,id',
            'referencia'   => 'nullable|string',
            'date'         => 'required|date',
            'pay_method_id'=> 'required|exists:pay_methods,id',
            'status'       => 'required|string',
            'productos'    => 'required|array|min:1',
            'productos.*.id'       => 'required|exists:products,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio'   => 'required|numeric|min:0',
            'productos.*.importe'  => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $compra = Purchase::findOrFail($id);

            /*
            |---------------------------------------------------
            | 1. REVERTIR STOCK ANTERIOR
            |---------------------------------------------------
            */
            $detallesAnteriores = DetailPurchase::where('purchase_id', $compra->id)->get();

            foreach ($detallesAnteriores as $detalle) {
                $product = Product::findOrFail($detalle->product_id);
                $product->decrement('stock', $detalle->cantidad);
            }

            /*
            |---------------------------------------------------
            | 2. ELIMINAR DETALLE ANTERIOR
            |---------------------------------------------------
            */
            DetailPurchase::where('purchase_id', $compra->id)->delete();

            /*
            |---------------------------------------------------
            | 3. ACTUALIZAR CABECERA DE COMPRA
            |---------------------------------------------------
            */
            $total = array_sum(array_column($request->productos, 'importe'));

            $compra->update([
                'contact_id'      => $request->proveedor_id,
                'boxe_opening_id' => $request->boxe_opening_id,
                'pay_method_id'   => $request->pay_method_id,
                'referencia'      => $request->referencia,
                'total'           => $total,
                'status'          => $request->status,
                'date'            => $request->date,
            ]);

            /*
            |---------------------------------------------------
            | 4. REGISTRAR NUEVO DETALLE + STOCK
            |---------------------------------------------------
            */
            foreach ($request->productos as $prod) {
                $product = Product::findOrFail($prod['id']);
                $product->increment('stock', $prod['cantidad']);

                DetailPurchase::create([
                    'purchase_id'    => $compra->id,
                    'product_id'     => $prod['id'],
                    'cantidad'       => $prod['cantidad'],
                    'precio_unitario'=> $prod['precio'],
                    'subtotal'       => $prod['importe'],
                ]);
            }

            /*
            |---------------------------------------------------
            | 5. ACTUALIZAR MOVIMIENTO DE CAJA
            |---------------------------------------------------
            */
            BoxeMovement::where('boxe_opening_id', $compra->boxe_opening_id)
                ->where('type', 'egreso')
                ->where('pay_method_id', $compra->pay_method_id)
                ->where('amount', $compra->total)
                ->latest()
                ->first()
                ?->update([
                    'amount'        => $total,
                    'pay_method_id' => $request->pay_method_id,
                    'date'          => now(),
                ]);

            DB::commit();

            return redirect()
                ->route('purchases')
                ->with('success', 'Compra actualizada correctamente.');

        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Error al actualizar la compra: ' . $e->getMessage());
        }
    }



    public function destroy(Request $request)
    {
        try {
            $purchase = Purchase::findOrFail($request->id);
            $purchase->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Compra eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
