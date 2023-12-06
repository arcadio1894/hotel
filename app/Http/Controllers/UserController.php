<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employer;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Faker\Factory as Faker;

class UserController extends Controller
{

    public function index()
    {
        $tipo='lista';
        $roles=Role::all();
        return view('user.index', compact('tipo','roles'));
    }
    public function index_eliminated()
    {
        $tipo='eliminados';

        return view('user.index', compact('tipo','roles'));
    }
    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $rol = $request->input('rol');
        $names = $request->input('name');
        $tipo = $request->input('tipo');

        if ($tipo == 'lista') {
            $query = User::select('users.*')->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->orderBy('users.id', 'DESC');
        } elseif ($tipo == 'eliminados') {
            $query = User::onlyTrashed()->select('users.*')->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->orderBy('users.id', 'DESC');
        }

        if ($rol) {
            $query->where('roles.id', $rol);
        }

        if ($names) {
            $query->where('users.name', 'like', "%" . $names . "%");
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
            $roles = $operation->roles->pluck('description')->implode(', ');
            array_push($arrayOperations, [
                "id" => $operation->id,
                "name" => $operation->name,
                "lastname" => $operation->lastname,
                "email" => $operation->email,
                "role_name" =>$roles,

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

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
        $user = new User;

        $user->name = $request->name.' '.$request->lastname;
        $user->email = $request->email;
        $user->password = bcrypt('hotel123');
        $user->save();
        $roleId = $request->input('role_id');
        $role = Role::find($roleId);

        if ($role) {
            $user->assignRole($role->name);
        }

        $employer = new Employer();
        $employer->user_id = $user->id;
        $employer->name = $request->name;
        $employer->lastname = $request->lastname;
        $employer->email = $user->email;
        $sinRolPosition = Position::where('name', 'Sin Rol')->first();
        $employer->position_id = $sinRolPosition->id;
        $employer->dni =mt_rand(10000000, 99999999);
        $employer->address ='sin direccion';
        $randomTimestamp = mt_rand(1, time());
        $randomDate = date("Y-m-d", $randomTimestamp);
        $employer->birth = $randomDate;
        $employer->phone =mt_rand(100000000, 999999999);
        $employer->save();
            DB::commit();

            return response()->json(['success' => 'Usuario creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el usuario. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::find($request->get('id'));

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->save();

            $roleId = $request->input('role_id');
            $role = Role::find($roleId);

            if ($role) {
                $user->syncRoles([$role->name]);
            }

            return response()->json(['success' => 'Usuario actualizado correctamente']);
        });
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $employer = Employer::where('user_id', $user->id)->first();
            if ($employer) {
                $employer->delete();
            }

            $customer = Customer::where('user_id', $user->id)->first();
            if ($customer) {
                $customer->delete();
            }
            $user->delete();

            DB::commit();

            return response()->json(['success' => 'Usuario eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el usuario. Detalles: ' . $e->getMessage()], 500);
        }

    }
}
