<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRolRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    const MODULES=[
        'permission'=> 'PERMISOS',
        'role' => 'ROLES',
        'user' => 'USUARIOS',
        'employer' => 'EMPLEADOS',
        'customer' => 'CLIENTES',
        'dashboard'=>'DASHBOARD'
    ];
    public function index()
    {
        $tipo='lista';
        return view('role.index', compact('tipo'));
    }

    public function store(StoreRoleRequest $request)
    {
        try {
            DB::beginTransaction();

            Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);

            DB::commit();

            return response()->json(['success' => 'Rol creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el rol. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateRolRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $role = Role::find($request->input('id'));
            $role->update([
                'name' => $request->input('name'),
                'description' => $request->input('description'),
            ]);

            DB::commit();

            return response()->json(['success' => 'Rol actualizado correctamente']);
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json(['error' => 'Error al actualizar el rol. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $rol = Role::find($id);

            if (!$rol) {
                return response()->json(['message' => 'El rol no existe'], 404);
            }

            $rol->delete();
            DB::commit();
            return response()->json(['message' => 'Rol eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el rol. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function editPermissions( $id )
    {
        $tipo='lista';
        $permissions = Permission::select('id', 'name', 'description')->get();

        $groupPermissions = [];
        $groups = [];
        foreach ( $permissions as $permission )
        {
            $pos = strpos($permission->name, '_');
            $group = substr($permission->name, $pos+1);
            array_push($groupPermissions, $group);
        }
        $grupos = array_unique($groupPermissions);
        foreach ( $grupos as $group )
        {
            array_push($groups, ['group'=>$group, 'name'=>$this::MODULES[$group]]);
        }
        $role = Role::find($id);

        $permissionsSelected = [];
        $permissions1 = $role->permissions;
        foreach ( $permissions1 as $permission )
        {
            array_push($permissionsSelected, $permission->name);
        }
        return view('role.permisos', compact('permissions', 'groups', 'permissionsSelected', 'role','tipo'));
    }

    public function savePermissions(Request $request,$id){
        try {
            DB::beginTransaction();
            $role=Role::findOrFail($id);
            $permissions = $request->get('permissions');
            $role->syncPermissions($permissions);
            DB::commit();
            return response()->json(['message' => 'Guardado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al asignar permisos al rol. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $description = $request->input('description');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Role::orderBy('id', 'DESC');
        }

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
