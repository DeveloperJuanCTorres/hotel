<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::all();

        return view('reservations.index', compact('reservations'));
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
}
