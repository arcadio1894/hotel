<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    function index(){
        $tipo='lista';
        $room_types = DB::table('room_types')->get()/*->pluck('name')->toArray()*/;
        return view('reservation.index', compact('tipo','room_types'));
    }

    public function getDataReservation(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        //$documentCliente = $request->input('document_cliente');
        //$name = $request->input('name');
        $type = $request->input('type');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Room::orderBy('id', 'ASC');
        }
        /*
        elseif($tipo=='eliminados'){
                $query = Room::onlyTrashed()->orderBy('id', 'DESC');
        }
        elseif ($tipo=='reporte'){
            $query = Room::withTrashed()->orderBy('id', 'DESC');
        }*/

        // Aplicar filtros si se proporcionan
        /*
        if ($documentCliente) {
            $query->where('document', $documentCliente);
        }

        if ($name) {
            $query->where('name', $name);
        }
        */

        if ($type) {
            $query->where('room_type_id', $type);
        }

        $totalFilteredRecords = $query->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);

        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $operations = $query->skip(($pageNumber - 1) * $perPage)
            ->take($perPage)
            ->get();

        $arrayOperations = [];

        foreach ( $operations as $operation )
        {
            array_push($arrayOperations, [
                "id" => $operation->id,
                "room_type_id" => $operation->room_type_id,
                "level" => $operation->level,
                "number" => $operation->number,
                "status" => $operation->status,

            ]);
        }

        $pagination = [
            'currentPage' => (int)$pageNumber,
            'totalPages' => (int)$totalPages,
            'startRecord' => $startRecord,
            'endRecord' => $endRecord,
            'totalRecords' => $totalFilteredRecords,
            'totalFilteredRecords' => $totalFilteredRecords
        ];

        return ['data' => $arrayOperations, 'pagination' => $pagination];

    }
}
