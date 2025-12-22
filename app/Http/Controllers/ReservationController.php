<?php

namespace App\Http\Controllers;

use App\Models\BoxeOpening;
use App\Models\Contact;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();
        $caja_abierta = BoxeOpening::where('status','abierta')->first();

        return view('reservations.index', compact('reservations','caja_abierta'));
    }

    public function list()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->get();
        return view('reservations.partials.list', compact('reservations'));
    }

    public function getDisponibles(Request $request)
    {
        $fechaInicio = $request->fecha_inicio . ' ' . $request->hora_inicio;
        $fechaFin = $request->fecha_fin . ' ' . $request->hora_fin;

        // Ejemplo: obtener habitaciones ocupadas en ese rango
        $ocupadas = Reservation::where(function($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha_inicio', [$fechaInicio, $fechaFin])
                ->orWhereBetween('fecha_fin', [$fechaInicio, $fechaFin])
                ->orWhere(function($q) use ($fechaInicio, $fechaFin) {
                    $q->where('fecha_inicio', '<=', $fechaInicio)
                        ->where('fecha_fin', '>=', $fechaFin);
                });
        })->pluck('room_id');

        // Ahora las disponibles son las que no están en ocupadas
        $habitaciones = Room::with('type')->whereNotIn('id', $ocupadas)->get();

        return response()->json($habitaciones);
    }

    public function store(Request $request)
    {
        try {
            $cliente = Contact::where('numero_doc', $request->numero_doc)->first();
            if ($cliente == null) {
                $contact = new Contact();
                $contact->tipo_doc = $request->tipo_doc;
                $contact->numero_doc = $request->numero_doc;
                $contact->name = $request->name;
                $contact->address = $request->address;
                $contact->type = 'CLIENTE';
                $contact->phone = $request->phone;
                $contact->save();
                $huesped = $contact;
            }
            else{
                $huesped = $cliente;
            }

            $reservation = new Reservation();
            $reservation->contact_id = $huesped->id;
            $reservation->room_id = $request->habitacion;
            $reservation->fecha_inicio = $request->fecha_inicio;
            $reservation->hora_inicio = $request->hora_inicio;
            $reservation->fecha_fin = $request->fecha_fin;
            $reservation->hora_fin = $request->hora_fin;
            $reservation->total = $request->total;
            $reservation->saldo = $request->total;
            $reservation->save();

            return response()->json([
                'status' => true,
                'msg' => 'La reserva se realizó exitosamente.'
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
            $reservation = Reservation::findOrFail($request->id);
            $reservation->delete();

            $room = Room::findOrFail($reservation->room_id);
            $room->status = "DISPONIBLE";
            $room->save();

            return response()->json([
                'status' => true,
                'msg' => 'Reservación eliminado correctamente.'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'msg' => $th->getMessage()
            ]);
        }
    }

    public function getByRoom($room_id)
    {
        $room = Room::findOrFail($room_id);

        if ($room->status !== 'RESERVADO') {
            return response()->json([
                'status' => false,
                'msg' => 'La habitación no está reservada'
            ]);
        }
        
        $reservation = Reservation::with('contact')
            ->where('room_id', $room_id)
            ->latest()
            ->first();

        if (!$reservation) {
            return response()->json([
                'status' => false,
                'msg' => 'No existe reserva activa'
            ]);
        }

        $noches = Carbon::parse($reservation->fecha_inicio)
            ->diffInDays(Carbon::parse($reservation->fecha_fin));

        return response()->json([
            'status' => true,
            'data' => [
                'reservation_id' => $reservation->id,
                'contact' => $reservation->contact,
                'fecha_inicio' => $reservation->fecha_inicio,
                'hora_inicio' => $reservation->hora_inicio,
                'fecha_fin' => $reservation->fecha_fin,
                'hora_fin' => $reservation->hora_fin,
                'noches' => $noches,
                'total' => $reservation->total,
                'saldo' => $reservation->saldo
            ]
        ]);
    }
}
