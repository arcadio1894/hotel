<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $title="Habitación";
        $tipo="Lista";
        $states= [
            'D' => 'Disponible',
            'O' => 'Ocupado',
            'R' => 'Reservado',
            'E' => 'En Espera',
            'L' => 'Limpieza',
            'F' => 'Fuera de Servicio',
        ];
        $types=RoomType::orderBy('name')->pluck('id', 'name');
        return view('room.index', compact("title",'tipo', 'states', 'types'));
    }

    public function showDeletes(){
        //$roomTypes = RoomType::onlyTrashed()->orderBy('deleted_at')->get();
        $title="Habitaciones Eliminadas";
        $tipo="Eliminados";
        return view('room.index', compact('title','tipo'));
    }

    public function getDataRoom(Request $request, $pageNumber = 1){
        $perPage = 10;

        $inputType = $request->input('inputType');
        $inputLevel = $request->input('inputLevel');
        $inputNumber = $request->input('inputNumber');
        $inputStatus = $request->input('inputStatus');
        $tipo = $request->input('tipo');
        if ($tipo == 'Lista') {
            $query = Room::with(['roomType'])->orderBy('status', 'ASC');
        } else{
            $query = Room::onlyTrashed()->orderBy('status', 'ASC');
        }

        if ($inputType) {
            $query->whereHas('roomType', function ($query) use ($inputType) {
                $query->where('name', $inputType);
            });
        }

        if ($inputLevel) {
            $query->where('level', $inputLevel);
        }
        if ($inputNumber) {
            $query->where('number', $inputNumber);
        }

        if ($inputStatus) {
            $query->where('status', $inputStatus);
        }
        $results = $query->get();


        $totalFilteredRecords = $results->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);
        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $rooms = $results->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arrayRooms = [];

        foreach ( $rooms as $room )
        {
            array_push($arrayRooms, [
                "id" => $room->id,
                "type_room_id" => $room->roomType->id,
                "type_room" => $room->roomType->name,
                "level" => $room->level,
                "number" => $room->number,
                "description" => $room->description,
                "image" => $room->image,
                "status" => $room->status,
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

        return ['data' => $arrayRooms, 'pagination' => $pagination];
    }

    public function store(StoreRoomRequest $request)
    {
        try {
            DB::beginTransaction();
            Room::create([
                'room_type_id' => $request->input('room_type'),
                'level' => $request->input('level'),
                'number' => $request->input('number'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'image' => $request->input('image', 'no_image.png'), // Valor por defecto si 'image' no está presente en la solicitud
            ]);
            DB::commit();
            return response()->json(['success' => 'Tipo de habitación creada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el tipo de habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }


    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            DB::beginTransaction();
            $room= Room::find($request->input('id'));
            $room->update([
                'room_type_id' => $request->input('room_type'),
                'level' => $request->input('level'),
                'number' => $request->input('number'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'image' => ($request->input('image')) ? ($request->input('image')) : 'no_image.png',
            ]);
            DB::commit();
            return response()->json(['success' => 'Habitación actualizada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar la habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Room  $room
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $roomType = Room::find($id);

            if (!$roomType) {
                return response()->json(['message' => 'La habitación no existe'], 404);
            }
            $roomType->delete();
            DB::commit();
            return response()->json(['message' => 'Habitación eliminada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar la habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $roomType = Room::onlyTrashed()->find($id);

            if (!$roomType) {
                return response()->json(['message' => 'La habitación no existe'], 404);
            }
            $roomType->restore();
            DB::commit();
            return response()->json(['message' => 'Habitación restaurada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al restaurar la habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }
}
