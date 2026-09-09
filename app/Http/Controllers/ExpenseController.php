<?php

namespace App\Http\Controllers;

use App\Models\BoxeMovement;
use App\Models\BoxeOpening;
use App\Models\CategorieExpense;
use App\Models\Expense;
use App\Models\PayMethod;
use Illuminate\Http\Request;

use App\Exports\ExpensesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('expenses.index', compact('expenses','caja_abierta'));
    }

    public function list()
    {
        $expenses = Expense::all();
        return view('expenses.partials.list', compact('expenses'));
    }

    public function getFormData()
    {
        $categorias = CategorieExpense::select('id', 'name')->get();   
        $metodos_pago = PayMethod::select('id', 'name')->get();       

        return response()->json([
            'categorias' => $categorias,
            'metodos_pago' =>$metodos_pago
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'categorie_expense_id' => 'required|exists:categorie_expenses,id',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
                'referencia' => 'nullable|string',
                'pay_method_id' => 'required|exists:pay_methods,id'
            ]);

            $validated['boxe_opening_id'] = $request->box_opening_id;

            $expense = Expense::create($validated);

            $movimiento_caja = new BoxeMovement();
            $movimiento_caja->boxe_opening_id = $request->box_opening_id;
            $movimiento_caja->type = 'egreso';
            $movimiento_caja->amount = $request->amount;
            $movimiento_caja->date = now();
            $movimiento_caja->description = $expense->referencia;
            $movimiento_caja->pay_method_id = $request->pay_method_id;
            $movimiento_caja->save();

            return response()->json([
                'status' => true,
                'msg' => 'Gasto registrado correctamente'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
        
    }

    public function edit(Request $request)
    {
        try {
            $expense = Expense::findOrFail($request->id);
            $categorias = CategorieExpense::select('id', 'name')->get();

            if ($expense) {
                return response()->json([
                    'status' => true,
                    'msg' => 'Gasto encontrado.',
                    'expense' => $expense,
                    'categorias' => $categorias
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro el gasto'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }   
    }

    public function update(Request $request)
    {
        try {
            $expense = Expense::findOrFail($request->id);
            $boxe_opening = BoxeOpening::where('status','abierta')->first();

            // Validar datos
            $request->validate([
                'categorie_expense_id' => 'required|exists:categorie_expenses,id',
                'date' => 'required|date',
                'description' => 'nullable|string',
                'amount' => 'required|numeric|min:0',
                'referencia' => 'nullable|string',
                'method' => 'required|string'
            ]);

            // Actualizar campos
            $expense->categorie_expense_id = $request->categorie_expense_id;
            $expense->date = $request->date;
            $expense->description = $request->description;
            $expense->amount = $request->amount;
            $expense->referencia = $request->referencia;
            $expense->method = $request->method;
            $expense->boxe_opening_id = $boxe_opening->id;
            
            $expense->save();

            return response()->json([
                'status' => true,
                'msg' => 'Gasto actualizado correctamente.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $expense = Expense::findOrFail($request->id);
            $expense->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Gasto eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }

    private function obtenerGastos()
    {
        return Expense::with(['catexpense','paymethod'])
            ->orderByDesc('date')
            ->get();
    }

    public function exportExcel()
    {
        $expenses = $this->obtenerGastos();

        return Excel::download(
            new ExpensesExport($expenses),
            'Gastos.xlsx'
        );
    }

    public function exportPdf()
    {
        $expenses = $this->obtenerGastos();

        $pdf = Pdf::loadView('expenses.pdf', compact('expenses'))
            ->setPaper('A4','landscape');

        return $pdf->download('Gastos.pdf');
    }
}
