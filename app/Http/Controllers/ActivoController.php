<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\BoxeOpening;
use App\Models\Room;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    public function index()
    {
        $activos = Activo::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        return view('activos.index', compact('activos','caja_abierta'));
    }

    public function list()
    {
        $activos = Activo::with('room')->get();
        return view('activos.partials.list', compact('activos'));
    }

    public function getFormData()
    {
        $rooms = Room::select('id', 'numero')->get();

        return response()->json([
            'rooms' => $rooms
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|max:50',
                'active' => 'required|boolean',
            ]);

            Activo::create($validated);

            return response()->json([
                'status' => true,
                'msg' => 'Activo registrado correctamente'
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
            $activo = Activo::findOrFail($request->id);
            $rooms = Room::select('id', 'numero')->get();

            return response()->json([
                'status' => true,
                'msg' => 'Activo encontrado',
                'activo' => $activo,
                'rooms' => $rooms
            ]);

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
            $activo = Activo::findOrFail($request->id);

            $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'status' => 'required|string|max:50',
                'active' => 'required|boolean',
            ]);

            $activo->update([
                'room_id' => $request->room_id,
                'name' => $request->name,
                'description' => $request->description,
                'status' => $request->status,
                'active' => $request->active,
            ]);

            return response()->json([
                'status' => true,
                'msg' => 'Activo actualizado correctamente'
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
            $activo = Activo::findOrFail($request->id);
            $activo->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Activo eliminado correctamente'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }
}
