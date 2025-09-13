<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\Taxonomy;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Taxonomy::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('categories.index', compact('categories','caja_abierta'));
    }

    public function list()
    {
        $categories = Taxonomy::all();
        return view('categories.partials.list', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:255',
            ]);

            $category = Taxonomy::create($validated);

            return response()->json([
                'status' => true,
                'msg' => 'Categoría registrada correctamente'
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
            $category = Taxonomy::findOrFail($request->id);

            if ($category) {

                return response()->json([
                    'status' => true,
                    'msg' => 'Categoría encontrada.',
                    'category' => $category
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro la categoría'
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
            $category = Taxonomy::findOrFail($request->id);

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
                'msg' => 'Categoría actualizada correctamente.'
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
            $category = Taxonomy::findOrFail($request->id);
            $category->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Categoría eliminada correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
