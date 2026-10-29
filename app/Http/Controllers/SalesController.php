<?php

namespace App\Http\Controllers;

use App\Models\BoxeMovement;
use App\Models\BoxeOpening;
use App\Models\Contact;
use App\Models\DetailSale;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\SalesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class SalesController extends Controller
{
    
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

            // return redirect()->route('pos')->with('success', 'Venta registrada con éxito.');
            return redirect()->route('sales.ticket', $venta->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function ticket($id)
    {
        $sale = Sale::with([
            'contact',
            'details.product',
            'payMethod'
        ])->findOrFail($id);

        return view('pos.ticket', compact('sale'));
    }


    public function index()
    {
        $contacts = Contact::orderBy('name')->get();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        $sales = Sale::with('contact')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('ventas', compact('sales', 'contacts', 'caja_abierta'));
    }

    public function show($id)
    {
        $sale = Sale::with([
            'contact',
            'details.product'
        ])->findOrFail($id);

        return response()->json($sale);
    }

    public function search(Request $request)
    {
        $sales = $this->ventasFiltradas($request)->get();

        return response()->json(
            $sales->map(function ($sale) {
                return [
                    'id' => $sale->id,
                    'fecha' => $sale->created_at->format('d/m/Y H:i'),
                    'cliente' => $sale->contact->name ?? '-',
                    'total' => (float) $sale->total,
                    'status' => $sale->status,
                    'cantidad_productos' => $sale->details->sum('cantidad'),
                ];
            })
        );
    }

    // public function search(Request $request)
    // {
    //     $query = Sale::with('contact');

    //     if ($request->from_date && $request->to_date) {
    //         $query->whereBetween('transaction_date', [
    //             $request->from_date,
    //             $request->to_date
    //         ]);
    //     }

    //     if ($request->contact_id) {
    //         $query->where('contact_id', $request->contact_id);
    //     }

    //     if ($request->payment_status) {
    //         $query->where('payment_status', $request->payment_status);
    //     }

    //     return response()->json(
    //         $query->orderBy('transaction_date', 'desc')->get()
    //     );
    // }

    private function ventasFiltradas(Request $request)
    {
        $request->validate([
            'from_date' => 'nullable|date',
            'to_date' => 'nullable|date|after_or_equal:from_date',
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'payment_status' => 'nullable|in:paid,due',
        ]);

        $query = Sale::with([
            'contact',
            'details.product',
            'payMethod'
        ]);

        if ($request->filled('from_date')) {
            $query->where(
                'created_at',
                '>=',
                Carbon::parse($request->from_date)->startOfDay()
            );
        }

        if ($request->filled('to_date')) {
            $query->where(
                'created_at',
                '<=',
                Carbon::parse($request->to_date)->endOfDay()
            );
        }

        if ($request->filled('contact_id')) {
            $query->where('contact_id', $request->contact_id);
        }

        if ($request->filled('payment_status')) {
            $estado = $request->payment_status === 'paid'
                ? 'pagado'
                : 'debido';

            $query->where('status', $estado);
        }

        return $query->orderBy('created_at', 'desc');
    }

    public function exportPdf(Request $request)
    {
        $sales = $this->ventasFiltradas($request)->get();

        $totalGeneral = $sales->sum('total');

        $pdf = Pdf::loadView('reports.sales_pdf', [
            'sales' => $sales,
            'totalGeneral' => $totalGeneral,
            'desde' => $request->from_date,
            'hasta' => $request->to_date,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download(
            'reporte_ventas_' . now()->format('Ymd_His') . '.pdf'
        );
    }

    public function exportExcel(Request $request)
    {
        $sales = $this->ventasFiltradas($request)->get();

        return Excel::download(
            new SalesExport($sales),
            'reporte_ventas_' . now()->format('Ymd_His') . '.xlsx'
        );
    }
}
