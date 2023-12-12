<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployerRequest;
use App\Http\Requests\UpdateEmployerRequest;
use App\Models\Employer;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployerController extends Controller
{

    public function index()
    {
        $tipo='lista';
        $positions = Position::all();
        return view('employer.index', compact('tipo','positions'));
    }
    public function index_eliminated()
    {
        $tipo='eliminados';
        $positions = Position::all();
        return view('employer.index', compact('tipo','positions'));
    }

    public function store(StoreEmployerRequest $request)
    {
        try {
            DB::beginTransaction();
            //dump($request);
            //dd($request);
            $user = new User;

            $user->name = $request->name.' '.$request->lastname;
            $user->email = $request->email;
            $user->password = Hash::make($request->dni);
            $user->save();

            $employer = new Employer();
            $employer->user_id = $user->id;
            $employer->name = $request->input('name');
            $employer->lastname = $request->input('lastname');
            $employer->position_id = $request->input('position_id');
            $employer->dni = $request->input('dni');
            $employer->address = $request->input('address');
            $employer->email = $request->email;
            $employer->birth = $request->input('birth');
            $employer->phone = $request->input('phone');
            $employer->save();

            DB::commit();

            return response()->json(['success' => 'Empleado creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el empleado. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function update(UpdateEmployerRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $employer = Employer::find($id);

            if (!$employer) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }

            $employer->name = $request->input('name');
            $employer->lastname = $request->input('lastname');
            $employer->position_id = $request->input('position_id');
            $employer->dni = $request->input('dni');
            $employer->address = $request->input('address');
            $employer->email = $request->email;
            $employer->birth = $request->input('birth');
            $employer->phone = $request->input('phone');
            $employer->save();

            DB::commit();

            return response()->json(['success' => 'Empleado actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el empleado. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $employer = Employer::find($id);

            if (!$employer) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }

            $employer->delete();

            $employer->user->delete();

            DB::commit();

            return response()->json(['success' => 'Empleado eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el empleado. Detalles: ' . $e->getMessage()], 500);
        }
    }
    public function restore($id)
    {
        try {
            DB::beginTransaction();

            $employer = Employer::onlyTrashed()->find($id);

            if (!$employer) {
                return response()->json(['error' => 'Empleado no encontrado'], 404);
            }

            $employer->restore();

            $user = User::onlyTrashed()->find($employer->user_id);

            if ($user) {
                $user->restore();
            }

            DB::commit();

            return response()->json(['message' => 'Empleado restaurado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al restaurar el empleado. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $dni = $request->input('document_employer');
        $names = $request->input('name');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Employer::orderBy('id', 'DESC');
        }
        elseif($tipo=='eliminados'){
            $query = Employer::onlyTrashed()->orderBy('id', 'DESC');
        }

        // Aplicar filtros si se proporcionan
        if ($dni) {
            $query->where('dni', $dni);
        }

        if ($names) {
            $query->where('name', "like" ,"%" . $names."%" );
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
                "lastname" => $operation->lastname,
                "position_name" => $operation->position->name,
                "position_id" => $operation->position->id,
                "dni" => $operation->dni,
                "address" => $operation->address,
                "email" => $operation->email,
                "birth" => Carbon::parse($operation->birth)->format('d/m/Y'),
                "phone" => $operation->phone,
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