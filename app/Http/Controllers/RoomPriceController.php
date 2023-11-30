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
        $levelRoom = $request->input('levelRoom');
        $numberRoom = $request->input('numberRoom');
        $priceRoom = $request->input('priceRoom');
        $durationHoursRoom = $request->input('durationHoursRoom');
        $query = RoomPrice::with(['room', 'season'])->orderBy('price', 'ASC');
        if ($nameSeason) {
            $query->whereHas('season', function ($query) use ($nameSeason) {
                $query->where('name', $nameSeason);
            });
        }
        if ($levelRoom) {
            $query->whereHas('room', function ($query) use ($levelRoom) {
                $query->where('level', $levelRoom);
            });
        }

        if ($numberRoom) {
            $query->whereHas('room', function ($query) use ($numberRoom) {
                $query->where('number', $numberRoom);
            });
        }
        if ($priceRoom) {
            $query->whereHas('room', function ($query) use ($priceRoom) {
                $query->where('price', $priceRoom);
            });
        }

        if ($durationHoursRoom) {
            $query->whereHas('room', function ($query) use ($durationHoursRoom) {
                $query->where('duration_hours', $durationHoursRoom);
            });
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
                "number" => $roomPrice->room->number,
                "level" => $roomPrice->room->level,
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
