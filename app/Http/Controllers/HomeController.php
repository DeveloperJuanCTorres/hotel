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
}
