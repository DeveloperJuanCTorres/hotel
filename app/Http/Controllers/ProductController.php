<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\IgvType;
use App\Models\Product;
use App\Models\Taxonomy;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('products.index', compact('products','caja_abierta'));
    }

    public function list()
    {
        $products = Product::with('taxonomy')->get();
        return view('products.partials.list', compact('products'));
    }

    public function getFormData()
    {
        $categorias = Taxonomy::select('id', 'name')->get();
        $unidades = Unit::select('id', 'name')->get();
        $igv = IgvType::select('id', 'name')->get();

       

        return response()->json([
            'categorias' => $categorias,
            'unidades' => $unidades,
            'igv' =>$igv,
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'taxonomy_id' => 'required|exists:taxonomies,id',
                'unit_id' => 'required|exists:units,id',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
                'igv_type_id' => 'required|exists:igv_types,id',
                'price_compra' => 'required|numeric|min:0',
            ]);

            if ($request->hasFile('image')) {
                // Guardar en storage/app/public/productos
                $path = $request->file('image')->store('products', 'public');
                $validated['image'] = $path; // ejemplo: "productos/abcd123.jpg"
            }

            $product = Product::create($validated);

            return response()->json([
                'status' => true,
                'msg' => 'Producto registrado correctamente'
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
            $product = Product::findOrFail($request->id);
            $categorias = Taxonomy::select('id', 'name')->get();
            $unidades = Unit::select('id', 'name')->get();
            $igv = IgvType::select('id', 'name')->get();

            if ($product) {
                $product->image = str_replace('\\', '/', $product->image);
                return response()->json([
                    'status' => true,
                    'msg' => 'Producto encontrado.',
                    'product' => $product,
                    'categorias' => $categorias,
                    'unidades' => $unidades,
                    'igv' => $igv
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro el producto'
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
            $product = Product::findOrFail($request->id);

            // Validar datos
            $request->validate([
                'name' => 'required|string|max:255',
                'taxonomy_id' => 'required|integer',
                'unit_id' => 'required|integer',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'price_compra' => 'required|numeric|min:0',
            ]);

            // Actualizar campos
            $product->name = $request->name;
            $product->taxonomy_id = $request->taxonomy_id;
            $product->unit_id = $request->unit_id;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock = $request->stock;
            $product->igv_type_id = $request->igv_type_id;
            $product->price_compra = $request->price_compra;

            // Subir nueva imagen si existe
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products/' . now()->format('F_Y'), 'public');
                $product->image = $imagePath;
            }

            $product->save();

            return response()->json([
                'status' => true,
                'msg' => 'Producto actualizado correctamente.'
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
            $product = Product::findOrFail($request->id);
            $product->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Producto eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
