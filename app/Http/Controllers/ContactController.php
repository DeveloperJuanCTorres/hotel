<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\Contact;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\AssignOp\Concat;

class ContactController extends Controller
{
    public function index()
    {
        $clients = Contact::where('type','CLIENTE')->get();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('clients.index', compact('clients','caja_abierta'));
    }

    public function list()
    {
        $clients = Contact::where('type','CLIENTE')->get();
        return view('clients.partials.list', compact('clients'));
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
            $contact->type = 'CLIENTE';
            $contact->save();

            return response()->json([
                'status' => true,
                'msg' => 'Cliente registrado correctamente'
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
            $contact = Contact::findOrFail($request->id);

            if ($contact) {

                return response()->json([
                    'status' => true,
                    'msg' => '  Cliente encontrado.',
                    'contact' => $contact
                ]);
            }
            else {
                return response()->json([
                    'status' => false,
                    'msg' => 'No se encontro al cliente'
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
                'msg' => 'Cliente actualizado correctamente.'
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
                'msg' => 'Cliente eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
