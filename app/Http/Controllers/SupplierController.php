<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Contact::where('type','PROVEEDOR')->get();

        return view('suppliers.index', compact('suppliers'));
    }

    public function list()
    {
        $suppliers = Contact::where('type','PROVEEDOR')->get();
        return view('suppliers.partials.list', compact('suppliers'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo_doc' => 'required|string|max:20',
                'numero_doc' => 'required|string|max:25',
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:25',
            ]);

            $contact = Contact::create($validated);
            $contact->type = 'PROVEEDOR';
            $contact->save();

            return response()->json([
                'status' => true,
                'msg' => 'Proveedor registrado correctamente'
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
            $suppliers = Contact::findOrFail($request->id);

            if ($suppliers) {

                return response()->json([
                    'status' => true,
                    'msg' => '  Proveedor encontrado.',
                    'contact' => $suppliers
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro al proveedor'
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
            $contact = Contact::findOrFail($request->id);

            // Validar datos
            $request->validate([
                'tipo_doc' => 'required|string|max:20',
                'numero_doc' => 'required|string|max:25',
                'name' => 'required|string|max:255',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:25',
            ]);

            // Actualizar campos
            $contact->tipo_doc = $request->tipo_doc;
            $contact->numero_doc = $request->numero_doc;
            $contact->name = $request->name;
            $contact->address = $request->address;
            $contact->email = $request->email;
            $contact->phone = $request->phone;

            $contact->save();

            return response()->json([
                'status' => true,
                'msg' => 'Proveedor actualizado correctamente.'
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
            $contact = Contact::findOrFail($request->id);
            $contact->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Proveedor eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
