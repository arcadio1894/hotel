<?php

namespace App\Http\Controllers;

use App\Models\RoomPrice;
use Illuminate\Http\Request;

class RoomPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        //$roomPrices= RoomPrice::orderBy('price')->get();
        $title="Precios de Habitación";
        $tipo="Lista";
        return view('roomPrice.index', compact("title",'tipo'));
    }

    public function getDataRoomPrice(Request $request, $pageNumber = 1){
        $perPage = 10;

        $nameSeason = $request->input('nameSeason');
        $typeRoom = $request->input('typeRoom');
        $priceRoom = $request->input('priceRoom');
        $durationHoursRoom = $request->input('durationHoursRoom');
        $query = RoomPrice::with(['room_type', 'season'])->orderBy('price', 'ASC');
        if ($nameSeason) {
            $query->whereHas('season', function ($query) use ($nameSeason) {
                $query->where('name', $nameSeason);
            });
        }

        if ($typeRoom) {
            $query->whereHas('room_type', function ($query) use ($typeRoom) {
                $query->where('name', $typeRoom);
            });
        }
        if ($priceRoom) {
            $query->where('price', $priceRoom);
        }

        if ($durationHoursRoom) {
            $query->where('duration_hours', $durationHoursRoom);
        }
        $results = $query->get();


        $totalFilteredRecords = $results->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);
        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $roomPrices = $results->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arrayRoomPrices = [];

        foreach ( $roomPrices as $roomPrice )
        {
            array_push($arrayRoomPrices, [
                "id" => $roomPrice->id,
                "season" => $roomPrice->season->name,
                "type_room" => $roomPrice->room_type->name,
                "price" => $roomPrice->price,
                "duration_hours" => $roomPrice->duration_hours,
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

        return ['data' => $arrayRoomPrices, 'pagination' => $pagination];
    }
}
