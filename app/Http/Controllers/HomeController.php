<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\Contact;
use App\Models\PayMethod;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

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
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        
        return view('dashboard',compact('caja_abierta'));
    }

    public function room()
    {
        // $rooms = Room::all();
        // return view('rooms.index', compact('rooms'));

        $today = Carbon::now();

        $rooms = Room::with('type', 'reservation')->get();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        $pay_methods = PayMethod::all();

        foreach ($rooms as $room) {
            $hasReservationToday = $room->reservation()
                ->whereDate('fecha_inicio', '<=', $today)
                ->whereDate('fecha_fin', '>=', $today)
                ->exists();

            if ($hasReservationToday) {
                $room->status = 'RESERVADO';
            }

            $room->save();
        }

        return view('rooms.index', compact('rooms','pay_methods', 'caja_abierta'));
    }

    public function list()
    {
        // $rooms = Room::with('type')->get();
        // return view('rooms.partials.list', compact('rooms'));

        $today = Carbon::now();

        $rooms = Room::with('type', 'reservation')->get();

        foreach ($rooms as $room) {
            $hasReservationToday = $room->reservation()
                ->whereDate('fecha_inicio', '<=', $today)
                ->whereDate('fecha_fin', '>=', $today)
                ->exists();

            if ($hasReservationToday) {
                $room->status = 'RESERVADO';
            }

            $room->save();
        }

        return view('rooms.partials.list', compact('rooms'));
    }

    public function roomRegister(Request $request)
    {
        try {
            $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
            if ($cliente == null) {
                $contact = new Contact();
                $contact->tipo_doc = $request->tipo_doc;
                $contact->numero_doc = $request->numero_doc;
                $contact->name = $request->cliente;
                $contact->address = $request->direccion;
                $contact->type = 'CLIENTE';
                $contact->save();
                $huesped = $contact;
            }
            else{
                $huesped = $cliente;
            }

            $transaction = new Transaction();
            $transaction->room_id = $request->id;
            $transaction->contact_id = $huesped->id;
            $transaction->cant_personas = $request->cant_per;
            $transaction->precio_unitario = $request->precio;
            $transaction->cant_noches = $request->cant_noches;
            $transaction->total = $request->precio * $request->cant_noches;
            $transaction->estado_pago = $request->estado_pago;
            $transaction->pay_method_id = $request->pay_method_id;
            $transaction->fecha_entrada = Carbon::now();
            $transaction->fecha_salida = $request->fecha_salida;
            $transaction->hora_salida = $request->hora_salida;
            $transaction->status = 0;
            $transaction->boxe_opening_id = $request->boxe_opening_id;
            $transaction->save();

            $transaction->ref_nro = "TR-" . $transaction->id;
            $transaction->save();

            $room = Room::findOrFail($request->id);
            $room->status = "OCUPADO";
            $room->save();
            return response()->json([
                'status' => true,
                'msg' => 'El huesped: ' . $request->cliente . ' se alojo exitosamente.'
            ]);         
            
            } catch (\Throwable $th) {
                return response()->json([
                    'status' => false,
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

    public function buscarTransaccion($id)
    {
        $habitacion = Room::where('status', 'OCUPADO')->findOrFail($id);
        

        // Ejemplo: buscar transacción activa de esa habitación
        $transaccion = Transaction::where('room_id', $habitacion->id)
            ->where('status', 0) // o el campo que uses
            ->first();

        if (!$transaccion) {
            return response()->json(['error' => 'No se encontró transacción activa'], 404);
        }

        return response()->json([
            'transaccion_id' => $transaccion->id]);
    }

    public function detalle($id)
    {
        $transaction = Transaction::findOrFail($id);
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        $pay_methods = PayMethod::all();

        return view('rooms.detalle', compact('transaction','caja_abierta','pay_methods'));
    }
}
