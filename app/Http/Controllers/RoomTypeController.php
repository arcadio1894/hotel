<?php

namespace App\Http\Controllers;

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
        $lista=true;
        return view('roomType.index', compact('roomTypes', "title",'lista'));
    }

    public function showDeletes(){
        $roomTypes = RoomType::onlyTrashed()->orderBy('deleted_at')->get();
        $title="Tipos de Habitación Eliminados";
        $lista=false;
        return view('roomType.index', compact('roomTypes', 'title','lista'));
    }

    public function store(Request $request)
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


    public function update(Request $request, RoomType $roomType)
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
}
