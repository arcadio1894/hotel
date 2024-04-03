<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Employer;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserController extends Controller
{

    public function index()
    {
        $tipo='lista';
        $roles=Role::all();
        $document_types = DB::table('document_types')->get()->pluck('name')->toArray();
        return view('user.index', compact('tipo','roles','document_types'));
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
            $roles_id = $operation->roles->pluck('id')->implode(', ');
            $document_type = DB::table('customers')
                ->where('user_id', $operation->id)
                ->value('document_type');
            // Consulta para obtener datos de employers
            $employerData = DB::table('employers')
                ->where('user_id', $operation->id)
                ->first();

            // Consulta para obtener datos de customers
            $customerData = DB::table('customers')
                ->where('user_id', $operation->id)
                ->first();


            array_push($arrayOperations, [
                "id" => $operation->id,
                "name" => $employerData ? $employerData->name: ($customerData ? $customerData->name : $operation->name),
                "lastname" => $employerData ? $employerData->lastname : ($customerData ? $customerData->lastname : $operation->lastname),
                "email" => $operation->email,
                "role_name" =>$roles,
                "role_id" =>$roles_id,
                "document_type" => $document_type,

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

            if ($role && $role->name === 'customer') {
                // Verifica las condiciones antes de crear el cliente
                if ($request->input('document_type') != 'RUC' && is_null($request->input('lastname'))) {
                    throw new \Exception('El Apellido es requerido');
                }
                $existingCustomer = Customer::where('document', $request->input('document'))->first();
                if ($request->input('document_type') != 'RUC' and $existingCustomer) {
                    throw new \Exception('El número de documento ya está registrado.');
                }
                if ($request->input('document_type') == 'DNI' and strlen($request->input('document')) != 8 ) {
                    throw new \Exception('El DNI debe ser de 8 Dígitos.');
                }
                if ($request->input('document_type') == 'CARNÉ DE EXTRANJERIA' and strlen($request->input('document')) != 12 ) {
                    throw new \Exception('El C.E. debe ser de 12 Dígitos.');
                }
                if ($request->input('document_type') == 'RUC' and strlen($request->input('document')) != 11 ) {
                    throw new \Exception('El RUC debe ser de 11 Dígitos.');
                }

                $customer = new Customer();
                $customer->user_id = $user->id;
                $customer->name = $request->name;
                $customer->lastname = $request->lastname;
                $customer->email = $user->email;

                $customer->document_type = $request->input('document_type');
                $customer->document = $request->input('document');
                $customer->name = $request->input('name');
                $customer->lastname = $request->input('lastname', null);
                $customer->phone =  mt_rand(100000000, 999999999);
                $customer->email = $user->email;
                $randomTimestamp = mt_rand(strtotime('1950-01-01'), strtotime('2000-12-31'));
                $randomDate =Carbon::createFromTimestamp($randomTimestamp)->format('d-m-Y');
                $customer->birth = $randomDate;
                $customer->address = 'sin direccion';

                $customer->save();

            } else {
                $employer = new Employer();
                $employer->user_id = $user->id;
                $employer->name = $request->name;
                $employer->lastname = $request->lastname;
                $employer->email = $user->email;
                $sinRolPosition = Position::where('name', 'Sin Rol')->first();
                $employer->position_id = $sinRolPosition->id;
                $employer->dni = mt_rand(10000000, 99999999);
                $employer->address = 'sin direccion';
                $randomTimestamp = mt_rand(1, time());
                $randomDate = date("Y-m-d", $randomTimestamp);
                $employer->birth = $randomDate;
                $employer->phone = mt_rand(100000000, 999999999);
                $employer->save();
            }
            DB::commit();

            return response()->json(['success' => 'Usuario creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el usuario. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        dd($request);
        return DB::transaction(function () use ($request) {
            $user = User::find($request->get('id'));

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user->name = $request->input('name').' '.$request->input('lastname');
            $user->email = $request->input('email');
            $roleId = $request->input('role_id');
            $role = Role::find($roleId);
            $user->save();

            if ($role->name === 'customer') {
                $customer = Customer::where('user_id', $user->id)->first();
                if ($customer) {
                    $customer->name = $request->input('name');
                    $customer->lastname = $request->input('lastname');
                    $customer->email = $user->email;
                    $customer->save();
                }
            } elseif($role && $role->name !== 'customer')  {
                $employer = Employer::where('user_id', $user->id)->first();
                if ($employer) {
                    $employer->name = $request->input('name');
                    $employer->lastname = $request->input('lastname');
                    $employer->email = $user->email;

                    $employer->save();
                }
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
