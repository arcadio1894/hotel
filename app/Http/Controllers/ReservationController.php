<?php

namespace App\Http\Controllers;

use App\Models\Reservation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    function index(){
        $tipo='lista';
        //$document_types = DB::table('document_types')->get()->pluck('name')->toArray();
    return view('reservation.index', compact('tipo'/*,'document_types'*/));
    }
}
