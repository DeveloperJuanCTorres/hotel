<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\CategorieExpense;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $categories = CategorieExpense::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('expenses.categories.index', compact('categories','caja_abierta'));
    }

    public function list()
    {
        $categories = CategorieExpense::all();
        return view('expenses.categories.partials.list', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            $category = CategorieExpense::create($validated);

            return response()->json([
                'status' => true,
                'msg' => 'Categoría de gasto registrada correctamente'
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
            $category = CategorieExpense::findOrFail($request->id);

            if ($category) {

                return response()->json([
                    'status' => true,
                    'msg' => 'Categoría de gasto encontrada.',
                    'category' => $category
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro la categoría de gasto'
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
            $category = CategorieExpense::findOrFail($request->id);

            // Validar datos
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            // Actualizar campos
            $category->name = $request->name;
            $category->description = $request->description;

            $category->save();

            return response()->json([
                'status' => true,
                'msg' => 'Categoría de gasto actualizada correctamente.'
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
            $category = CategorieExpense::findOrFail($request->id);
            $category->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Categoría de gasto eliminada correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
