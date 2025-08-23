<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function room()
    {
        $rooms = Room::all();
        return view('rooms.index', compact('rooms'));
    }

    public function list()
    {
        $rooms = Room::with('type')->get();
        return view('rooms.partials.list', compact('rooms'));
    }

    public function roomRegister(Request $request)
    {
        try {
            $cliente = $request->cliente;
            $room = Room::findOrFail($request->id);
            $room->status = "OCUPADO";
            $room->save();
            return response()->json([
                'status' => true,
                'msg' => 'El cliente: ' . $cliente . ' se alojo exitosamente.'
            ]);         
            
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => true,
                    'msg' => $th->getMessage()
                ]);
        }        
    }

    public function updateStatus(Request $request)
    {
        try {
            if ($request->status == "LIMPIEZA") {
                $room = Room::findOrFail($request->id);
                $room->status = "DISPONIBLE";
                $room->save();
                return response()->json([
                    'status' => true,
                    'msg' => 'Estado de la habitación actualizado correctamente.'
                ]);
            }  
            else{
                return response()->json([
                    'status' => false,
                    'msg' => 'El estado no es LIMPIEZA.'
                ]);
            }           
            
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => true,
                    'msg' => $th->getMessage()
                ]);
        }        
    }

    public function buscarDocumento(Request $request)
    {
        $tipo = $request->input('tipo_doc');
        $numero = $request->input('numero_doc');

        $token = "e25abbe4-79ad-4994-a467-c7921390743b-f3528f9c-1468-4f1f-ab91-5fb34dc83c03";

        if($tipo === 'DNI'){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://ruc.com.pe/api/v1/consultas", [
                "token" => $token,
                "dni"   => $numero
            ]);
        } elseif($tipo === 'RUC'){
            $response = Http::withHeaders([
                'Content-Type' => 'application/json'
            ])->post("https://ruc.com.pe/api/v1/consultas", [
                "token" => $token,
                "ruc"   => $numero
            ]);
        } else {
            return response()->json(['success' => false, 'msg' => 'Tipo no válido']);
        }

        if($response->successful()){
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        }

        return response()->json(['success' => false, 'msg' => 'Error en API externa']);
    }
}
