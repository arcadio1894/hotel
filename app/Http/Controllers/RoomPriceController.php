<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomPriceRequest;
use App\Http\Requests\UpdateRoomPriceRequest;
use App\Models\RoomPrice;
use App\Models\RoomType;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $types=RoomType::orderBy('name')->pluck('id', 'name');
        $seasons=Season::orderBy('name')->pluck('id', 'name');
        return view('roomPrice.index', compact("title",'tipo', 'types', 'seasons'));
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
                "season" => $roomPrice->season ? $roomPrice->season->name : null,
                "type_room" => $roomPrice->room_type->name,
                "season_id" => $roomPrice->season ? $roomPrice->season->id : null,
                "type_room_id" => $roomPrice->room_type->id,
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

    public function store(StoreRoomPriceRequest $request)
    {
        try {
            DB::beginTransaction();
            RoomPrice::create([
                'room_type_id' => $request->input('room_type'),
                'season_id' => $request->input('season'),
                'duration_hours' => $request->input('duration_hours'),
                'price' => $request->input('price'),
            ]);
            DB::commit();
            return response()->json(['success' => 'Precio creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el precio. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateRoomPriceRequest $request, RoomPrice $room)
    {
        try {
            DB::beginTransaction();
            $roomPrice= RoomPrice::find($request->input('id'));
            $roomPrice->update([
                'room_type_id' => $request->input('room_type'),
                'season_id' => $request->input('season'),
                'duration_hours' => $request->input('duration_hours'),
                'price' => $request->input('price'),
            ]);
            DB::commit();
            return response()->json(['success' => 'Precio actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el Precio. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $roomType = RoomPrice::find($id);

            if (!$roomType) {
                return response()->json(['message' => 'El precio no existe'], 404);
            }
            $roomType->delete();
            DB::commit();
            return response()->json(['message' => 'Precio eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el precio. Detalles: ' . $e->getMessage()], 500);
        }
    }
}
