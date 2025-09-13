<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KardexController extends Controller
{
    public function index(Request $request)
    {
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        $productos = DB::table('products')->select('id', 'name')->get();

        $movimientos = collect();
        $stock_final = 0;

        if ($request->filled('product_id')) {
            $productId = $request->product_id;
            $desde = $request->from_date;
            $hasta = $request->to_date;

            // ENTRADAS
            $entradas = DB::table('detail_purchases')
                ->select(
                    'detail_purchases.created_at as fecha',
                    'purchases.id as referencia',
                    DB::raw("'COMPRA' as tipo"),
                    'detail_purchases.cantidad',
                    'detail_purchases.precio_unitario'
                )
                ->join('purchases', 'detail_purchases.purchase_id', '=', 'purchases.id')
                ->where('detail_purchases.product_id', $productId);
                // ->where('purchases.status', 'pagado');

            // SALIDAS
            $salidas = DB::table('detail_sales')
                ->select(
                    'detail_sales.created_at as fecha',
                    'sales.id as referencia',
                    DB::raw("'VENTA' as tipo"),
                    DB::raw('-detail_sales.cantidad as cantidad'),
                    'detail_sales.precio_unitario'
                )
                ->join('sales', 'detail_sales.sale_id', '=', 'sales.id')
                ->where('detail_sales.product_id', $productId)
                ->where('sales.status', 'pagado');

            // FILTROS FECHA (opcional)
            if ($desde) {
                $entradas->whereDate('detail_purchases.created_at', '>=', $desde);
                $salidas->whereDate('detail_sales.created_at', '>=', $desde);
            }
            if ($hasta) {
                $entradas->whereDate('detail_purchases.created_at', '<=', $hasta);
                $salidas->whereDate('detail_sales.created_at', '<=', $hasta);
            }

            // UNIÓN Y ORDEN
            $movs = $entradas->unionAll($salidas);
            $movs = DB::query()
                ->fromSub($movs, 'k')
                ->orderBy('k.fecha')
                ->get();

            // Calcular stock y costo promedio
            $stock = 0;
            $costoAcum = 0;
            $costoProm = 0;

            $movimientos = $movs->map(function ($m) use (&$stock, &$costoTotal, &$costoProm) {
                if ($m->tipo === 'COMPRA') {
                    $entradaCant = $m->cantidad;
                    $entradaCosto = $entradaCant * $m->precio_unitario;

                    $costoTotal += $entradaCosto;
                    $stock += $entradaCant;
                    $costoProm = $stock > 0 ? $costoTotal / $stock : 0;
                } else {
                    $salidaCant = abs($m->cantidad);
                    $salidaCosto = $salidaCant * $costoProm;

                    $costoTotal -= $salidaCosto;
                    $stock -= $salidaCant;
                }

                $m->stock = $stock;
                $m->costo_promedio = $costoProm;
                $m->costo_total = $costoTotal;

                return $m;
            });

            $stock_final = DB::table('products')->where('id', $productId)->value('stock');
        }

        return view('kardex.index', compact('productos', 'movimientos', 'stock_final','caja_abierta'));
    }
}
