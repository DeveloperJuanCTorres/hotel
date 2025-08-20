<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

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
}
