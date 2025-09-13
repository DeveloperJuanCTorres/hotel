<?php

namespace App\Http\Controllers;

use App\Models\Box;
use App\Models\BoxeClosure;
use App\Models\BoxeOpening;
use App\Models\Expense;
use App\Models\PayMethod;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoxeOpeningController extends Controller
{
    public function index()
    {
        $boxes = BoxeOpening::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('boxes.index', compact('boxes','caja_abierta'));
    }

    public function list()
    {
        $boxes = BoxeOpening::all();
        return view('boxes.partials.list', compact('boxes'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'monto_inicial' => 'required|numeric|min:0|max:9999|regex:/^\d+(\.\d{1,2})?$/'
            ]);

            // $opening = BoxeOpening::create($validated);
            $opening = new BoxeOpening();
            $opening->boxe_id = $request->boxe_id;
            $opening->user_id = auth()->id();
            $opening->monto_inicial = $request->monto_inicial;
            $opening->fecha_apertura = now();
            $opening->status = 'abierta';

            $opening->save();

            return response()->json([
                'status' => true,
                'msg' => 'Apertura de caja exitoso'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
        
    }

    public function getFormData()
    {
        $cajas = Box::select('id', 'name')->get();

        return response()->json([
            'cajas' => $cajas,
        ]);
    }

    public function cerrarCaja1()
    {
        try {
            $boxeOpening = BoxeOpening::where('status', 'abierta')->first();

            if (!$boxeOpening) {
                return response()->json(['msg' => 'No hay caja abierta'], 404);
            }

            // --- cálculos de ingresos y egresos (mismo que antes) ---
            $ingresos = Transaction::select('transactions.pay_method_id', 'pay_methods.name as metodo', DB::raw('SUM(transactions.total) as total'))
                ->join('pay_methods', 'transactions.pay_method_id', '=', 'pay_methods.id')
                ->where('transactions.estado_pago', 'pagado')
                ->whereBetween('transactions.fecha_entrada', [$boxeOpening->fecha_apertura, now()])
                ->groupBy('transactions.pay_method_id', 'pay_methods.name')
                ->get();

            $egresos = Expense::select('expenses.pay_method_id', 'pay_methods.name as metodo', DB::raw('SUM(expenses.amount) as total'))
                ->join('pay_methods', 'expenses.pay_method_id', '=', 'pay_methods.id')
                ->where('expenses.boxe_opening_id', $boxeOpening->id)
                ->groupBy('expenses.pay_method_id', 'pay_methods.name')
                ->get();

            $ingresosMap = $ingresos->keyBy('pay_method_id');
            $egresosMap  = $egresos->keyBy('pay_method_id');

            $payMethods = PayMethod::whereIn('id', $ingresos->pluck('pay_method_id')->merge($egresos->pluck('pay_method_id'))->unique())->get();

            $detalles = $payMethods->map(function ($pm) use ($ingresosMap, $egresosMap) {
                $ingreso = $ingresosMap->get($pm->id)->total ?? 0;
                $egreso  = $egresosMap->get($pm->id)->total ?? 0;
                return [
                    'metodo'  => $pm->name,
                    'ingresos'=> $ingreso,
                    'egresos' => $egreso,
                    'balance' => $ingreso - $egreso,
                ];
            });

            $totalIngresos = $detalles->sum('ingresos');
            $totalEgresos  = $detalles->sum('egresos');
            $balanceFinal  = $boxeOpening->monto_inicial + $totalIngresos - $totalEgresos;

            // --- guardar cierre ---
            $closure = BoxeClosure::create([
                'boxe_opening_id' => $boxeOpening->id,
                'monto_inicial'   => $boxeOpening->monto_inicial,
                'total_ingresos'  => $totalIngresos,
                'total_egresos'   => $totalEgresos,
                'balance_final'   => $balanceFinal,
                'detalle'         => $detalles,
                'fecha_cierre'    => now(),
            ]);

            // --- actualizar boxe_opening ---
            $boxeOpening->update([
                'monto_final'  => $balanceFinal,
                'fecha_cierre' => now(),
                'status'       => 'cerrada',
            ]);

            return response()->json([
                'msg'     => 'Caja cerrada exitosamente',
                'closure' => $closure
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function previewCierre($id)
    {
        try {
            $boxeOpening = BoxeOpening::findOrFail($id);

            $detalles = collect();

            $ingresosTransactions = DB::table('transactions')
                ->select(
                    'transactions.pay_method_id',
                    'pay_methods.name as metodo',
                    DB::raw('SUM(transactions.total) as total')
                )
                ->join('pay_methods', 'transactions.pay_method_id', '=', 'pay_methods.id')
                ->where('transactions.estado_pago', 'pagado')
                ->where('transactions.boxe_opening_id', $boxeOpening->id)
                ->groupBy('transactions.pay_method_id', 'pay_methods.name');

            $ingresosSales = DB::table('sales')
                ->select(
                    'sales.pay_method_id',
                    'pay_methods.name as metodo',
                    DB::raw('SUM(sales.total) as total')
                )
                ->join('pay_methods', 'sales.pay_method_id', '=', 'pay_methods.id')
                ->where('sales.status', 'pagado')
                ->where('sales.boxe_opening_id', $boxeOpening->id)
                ->groupBy('sales.pay_method_id', 'pay_methods.name');

            $ingresos = $ingresosTransactions
                ->unionAll($ingresosSales);

            $ingresos = DB::query()
                ->fromSub($ingresos, 'u')
                ->select('u.pay_method_id', 'u.metodo', DB::raw('SUM(u.total) as total'))
                ->groupBy('u.pay_method_id', 'u.metodo')
                ->get();

            // --- EGRESOS agrupados por método de pago ---
            $egresosExpenses = Expense::select(
                    'expenses.pay_method_id',
                    'pay_methods.name as metodo',
                    DB::raw('SUM(expenses.amount) as total')
                )
                ->join('pay_methods', 'expenses.pay_method_id', '=', 'pay_methods.id')
                ->where('expenses.boxe_opening_id', $boxeOpening->id)
                ->groupBy('expenses.pay_method_id', 'pay_methods.name');

            // Egresos desde purchases
            $egresosPurchases = Purchase::select(
                    'purchases.pay_method_id',
                    'pay_methods.name as metodo',
                    DB::raw('SUM(purchases.total) as total')
                )
                ->join('pay_methods', 'purchases.pay_method_id', '=', 'pay_methods.id')
                ->where('purchases.boxe_opening_id', $boxeOpening->id)
                // ->where('purchases.status', 'pagado')
                ->groupBy('purchases.pay_method_id', 'pay_methods.name');

            // Unir ambos
            $egresos = $egresosExpenses
                ->unionAll($egresosPurchases);

            $egresos = DB::query()
                ->fromSub($egresos, 'e')
                ->select('e.pay_method_id', 'e.metodo', DB::raw('SUM(e.total) as total'))
                ->groupBy('e.pay_method_id', 'e.metodo')
                ->get();

            $ingresosMap = $ingresos->keyBy('pay_method_id');
            $egresosMap  = $egresos->keyBy('pay_method_id');

            // Reunimos todos los métodos que aparecen en ingresos o egresos
            $payMethodIds = $ingresos->pluck('pay_method_id')
                ->merge($egresos->pluck('pay_method_id'))
                ->unique()
                ->values();

            // Si no hay movimientos, igual devolvemos colecciones vacías y totales en 0
            if ($payMethodIds->isNotEmpty()) {
                $payMethods = PayMethod::whereIn('id', $payMethodIds)->get();

                $detalles = $payMethods->map(function ($pm) use ($ingresosMap, $egresosMap) {
                    $ingreso = (float) ($ingresosMap->get($pm->id)->total ?? 0);
                    $egreso  = (float) ($egresosMap->get($pm->id)->total ?? 0);

                    return [
                        'metodo'   => $pm->name,
                        'ingresos' => $ingreso,
                        'egresos'  => $egreso,
                        'balance'  => $ingreso - $egreso,
                    ];
                });
            }

            $totalIngresos = (float) $detalles->sum('ingresos');
            $totalEgresos  = (float) $detalles->sum('egresos');
            $balanceFinal  = (float) $boxeOpening->monto_inicial + $totalIngresos - $totalEgresos;

            return response()->json([
                'detalle' => $detalles->values(), // aseguramos array indexado
                'totales' => [
                    'monto_inicial'  => (float) $boxeOpening->monto_inicial,
                    'total_ingresos' => $totalIngresos,
                    'total_egresos'  => $totalEgresos,
                    'balance_final'  => $balanceFinal,
                ]
            ]);
        } catch (\Throwable $e) {
            // devolvemos JSON de error (para que fetch no reviente por HTML)
            return response()->json([
                'error' => true,
                'msg'   => $e->getMessage(),
                'line'  => $e->getLine(),
                'file'  => class_basename($e->getFile()),
            ], 500);
        }
    }

    public function cerrarCaja(Request $request, $id)
    {
        $boxeOpening = BoxeOpening::findOrFail($id);

        // Recibimos los datos del preview
        $detalles = $request->input('detalle', []);
        $totales  = $request->input('totales', []);

        // Crear el cierre
        $closure = BoxeClosure::create([
            'boxe_opening_id' => $boxeOpening->id,
            'monto_inicial'   => $totales['monto_inicial'] ?? 0,
            'total_ingresos'  => $totales['total_ingresos'] ?? 0,
            'total_egresos'   => $totales['total_egresos'] ?? 0,
            'balance_final'   => $totales['balance_final'] ?? 0,
            'detalle'         => json_encode($detalles),
            'fecha_cierre'    => now(),
        ]);

        // Actualizar apertura de caja
        $boxeOpening->update([
            'monto_final'  => $totales['balance_final'] ?? 0,
            'fecha_cierre' => now(),
            'status'       => 'cerrada',
        ]);

        return response()->json([
            'status'   => true,
            'msg'      => 'Caja cerrada correctamente',
            'printUrl' => route('caja.print', $closure->id),
        ]);
    }

    public function print($id)
    {
        $closure = BoxeClosure::with('boxeOpening')->findOrFail($id);

        $detalle = json_decode($closure->detalle, true) ?? [];

        return view('boxes.partials.print', [
            'closure' => $closure,
            'detalle' => $detalle,
        ]);
    }
}
