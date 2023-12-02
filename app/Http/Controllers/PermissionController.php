<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\UpdatePermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
        $tipo='lista';
        return view('permission.index', compact('tipo'));
    }

    public function store(StorePermissionRequest $request)
    {
        try {
            DB::beginTransaction();

            $permission = new Permission;
            $permission->name = $request->name;
            $permission->description = $request->description;

            $permission->save();

            DB::commit();
            return response()->json(['message' => 'Permiso guardado con éxito.'], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear este permiso. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdatePermissionRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['error' => 'Permiso no encontrado'], 404);
            }

            $permission->name = $request->get('name');
            $permission->description = $request->get('description');

            $permission->save();

            DB::commit();

            return response()->json(['message' => 'Permiso actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el permiso. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $permission = Permission::find($id);

            if (!$permission) {
                return response()->json(['error' => 'Permiso no encontrado'], 404);
            }

            $permission->delete();

            DB::commit();

            return response()->json(['message' => 'Permiso eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el permiso. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $description = $request->input('description');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Permission::orderBy('id', 'DESC');
        }

        // Aplicar filtros si se proporcionan

        if ($description) {
            $query->where('description', "like" ,"%" .$description."%" );
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
                "name" => $operation->name,
                "description" => $operation->description,
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
