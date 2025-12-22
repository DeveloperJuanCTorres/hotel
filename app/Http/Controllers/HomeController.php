<?php

namespace App\Http\Controllers;

use App\Models\BoxeMovement;
use App\Models\BoxeOpening;
use App\Models\Contact;
use App\Models\PayMethod;
use App\Models\Product;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
        $hoy = now()->toDateString();

        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        $clientes = Contact::where('type','CLIENTE')->count();
        $productos = Product::count();
        $movimientos = BoxeMovement::orderBy('date', 'desc')->get();
        $ingresos = BoxeMovement::where('type', 'ingreso')
            ->whereDate('date', $hoy)
            ->sum('amount');
        $egresos = BoxeMovement::where('type', 'egreso')
            ->whereDate('date', $hoy)
            ->sum('amount');
        
         // --- Ingresos reales por mes del año actual ---
        $ingresosBD = BoxeMovement::selectRaw('MONTH(date) as mes, SUM(amount) as total')
            ->where('type', 'ingreso')
            ->whereYear('date', now()->year)
            ->groupByRaw('MONTH(date)')
            ->pluck('total','mes'); 
            // Esto devuelve: [1 => 1500, 3 => 200, ...]

        // --- Llenamos los 12 meses con 0 si no existen ---
        $ingresosMensuales = collect(range(1,12))->map(function($mes) use ($ingresosBD) {
            return [
                'mes' => $mes,
                'total' => (float) ($ingresosBD[$mes] ?? 0)
            ];
        });       
        
        
        return view('dashboard',compact('caja_abierta','clientes','productos','ingresos','egresos', 'ingresosMensuales', 'movimientos'));
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

    // public function roomRegister(Request $request)
    // {
    //     try {
    //         $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
    //         if ($cliente == null) {
    //             $contact = new Contact();
    //             $contact->tipo_doc = $request->tipo_doc;
    //             $contact->numero_doc = $request->numero_doc;
    //             $contact->name = $request->cliente;
    //             $contact->address = $request->direccion;
    //             $contact->type = 'CLIENTE';
    //             $contact->save();
    //             $huesped = $contact;
    //         }
    //         else{
    //             $huesped = $cliente;
    //         }

    //         $transaction = new Transaction();
    //         $transaction->room_id = $request->id;
    //         $transaction->contact_id = $huesped->id;
    //         $transaction->cant_personas = $request->cant_per;
    //         $transaction->precio_unitario = $request->precio;
    //         $transaction->cant_noches = $request->cant_noches;
    //         $transaction->total = $request->precio * $request->cant_noches;
    //         $transaction->estado_pago = $request->estado_pago;
    //         $transaction->pay_method_id = $request->pay_method_id;
    //         $transaction->fecha_entrada = Carbon::now();
    //         $transaction->fecha_salida = $request->fecha_salida;
    //         $transaction->hora_salida = $request->hora_salida;
    //         $transaction->status = 0;
    //         $transaction->boxe_opening_id = $request->boxe_opening_id;
    //         $transaction->save();

    //         $transaction->ref_nro = "TR-" . $transaction->id;
    //         $transaction->save();

    //         if ($request->filled('reservation_id')) {
    //             Reservation::where('id', $request->reservation_id)->delete();
    //         }

    //         $room = Room::findOrFail($request->id);
    //         $room->status = "OCUPADO";
    //         $room->save();
    //         return response()->json([
    //             'status' => true,
    //             'msg' => 'El huesped: ' . $request->cliente . ' se alojo exitosamente.'
    //         ]);         
            
    //         } catch (\Throwable $th) {
    //             return response()->json([
    //                 'status' => false,
    //                 'msg' => $th->getMessage()
    //             ]);
    //     }        
    // }

    public function roomRegister(Request $request)
    {
        DB::beginTransaction();

        try {
            // 👤 Cliente
            $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
            if (!$cliente) {
                $cliente = Contact::create([
                    'tipo_doc' => $request->tipo_doc,
                    'numero_doc' => $request->numero_doc,
                    'name' => $request->cliente,
                    'address' => $request->direccion,
                    'type' => 'CLIENTE',
                ]);
            }

            // 💰 Transacción
            $transaction = Transaction::create([
                'room_id' => $request->id,
                'contact_id' => $cliente->id,
                'cant_personas' => $request->cant_per,
                'precio_unitario' => $request->precio,
                'cant_noches' => $request->cant_noches,
                'total' => $request->precio * $request->cant_noches,
                'estado_pago' => $request->estado_pago,
                'pay_method_id' => $request->pay_method_id,
                'fecha_entrada' => now(),
                'fecha_salida' => $request->fecha_salida,
                'hora_salida' => $request->hora_salida,
                'status' => 0,
                'boxe_opening_id' => $request->boxe_opening_id
            ]);

            $transaction->update([
                'ref_nro' => 'TR-' . $transaction->id
            ]);

            // 🟡 CERRAR RESERVA SI EXISTE
            if ($request->filled('reservation_id')) {
                Reservation::where('id', $request->reservation_id)->delete();
            }

            // 🏨 OCUPAR HABITACIÓN
            Room::where('id', $request->id)->update([
                'status' => 'OCUPADO'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'msg' => 'El huésped se alojó exitosamente.'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();

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
        // $habitacion = Room::where('status', 'OCUPADO')->findOrFail($id);
        $habitacion = Room::where('id', $id)
            ->where('status', 'OCUPADO')
            ->firstOrFail();
        

        // Ejemplo: buscar transacción activa de esa habitación
        // $transaccion = Transaction::where('room_id', $habitacion->id)
        //     ->where('status', 0) // o el campo que uses
        //     ->first();

        $transaccion = Transaction::where('room_id', $habitacion->id)
            ->where('status', 0) // activa
            ->latest('id')
            ->first();

        if (!$transaccion) {
            return response()->json(['error' => 'No se encontró transacción activa'], 404);
        }

        return response()->json([
            'transaccion_id' => $transaccion->id]);
    }

    public function detalle($id)
    {
        // $transaction = Transaction::findOrFail($id);
        $transaction = Transaction::with([
            'contact',
            'room.type'
        ])->findOrFail($id);
        
        $caja_abierta = BoxeOpening::where('status','abierta')->first();
        $pay_methods = PayMethod::all();

        return view('rooms.detalle', compact('transaction','caja_abierta','pay_methods'));
    }
}
