<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoomTypeRequest;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        $roomTypes = RoomType::orderBy('name')->get();
        $title="Tipos de Habitación";
        $tipo="Lista";
        return view('roomType.index', compact('roomTypes', "title",'tipo'));
    }

    public function showDeletes(){
        $roomTypes = RoomType::onlyTrashed()->orderBy('deleted_at')->get();
        $title="Tipos de Habitación Eliminados";
        $tipo="Eliminados";
        return view('roomType.index', compact('roomTypes', 'title','tipo'));
    }

    public function store(RoomTypeRequest $request)
    {
        try {
            DB::beginTransaction();
            $roomType = new RoomType();
            $roomType->name = $request->input('name');
            $roomType->description = $request->input('description');
            $roomType->capacity = $request->input('capacity');
            $roomType->save();
            DB::commit();
            return response()->json(['success' => 'Tipo de habitación creada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el tipo de habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }


    public function update(RoomTypeRequest $request, RoomType $roomType)
    {
        try {
            DB::beginTransaction();
            $roomType = RoomType::find($request->input('id'));
            $roomType->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'capacity' => $request->input('capacity'),
            ]);
            DB::commit();
            return response()->json(['success' => 'Tipo de habitación actualizada correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el tipo de habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $roomType = RoomType::find($id);

            if (!$roomType) {
                return response()->json(['message' => 'El tipo de habitación no existe'], 404);
            }
            $roomType->delete();
            DB::commit();
            return response()->json(['message' => 'Tipo de habitación eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el tipo de habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $roomType = RoomType::onlyTrashed()->find($id);

            if (!$roomType) {
                return response()->json(['message' => 'El tipo de habitación no existe'], 404);
            }
            $roomType->restore();
            DB::commit();
            return response()->json(['message' => 'Tipo de habitación restaurado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al restaurar el tipo de habitación. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function getDataRoomType(Request $request, $pageNumber = 1){
        $perPage = 10;

        $nameRoomType = $request->input('nameRoomType');
        $tipo = $request->input('tipo');
        if ($tipo == 'Lista') {
            $query = RoomType::orderBy('name', 'ASC');
        } else{
            $query = RoomType::onlyTrashed()->orderBy('name', 'ASC');
        }
        if ($nameRoomType) {
            $query->where('name', $nameRoomType);
        }
        $results = $query->get();


        $totalFilteredRecords = $results->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);
        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $roomTypes = $results->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arrayRoomTypes = [];

        foreach ( $roomTypes as $roomType )
        {
            array_push($arrayRoomTypes, [
                "id" => $roomType->id,
                "name" => $roomType->name,
                "description" => $roomType->description,
                "capacity" => $roomType->capacity
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

        return ['data' => $arrayRoomTypes, 'pagination' => $pagination];
    }
}
