<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployerRequest;
use App\Http\Requests\UpdateEmployerRequest;
use App\Models\Employer;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployerController extends Controller
{

    public function index()
    {
        $employers = Employer::all();
        $positions = Position::all();
        return view('employer.index', compact('employers','positions'));
    }
    public function index_eliminated()
    {
        $employers = Employer::onlyTrashed()->orderBy('deleted_at')->get();
        $positions = Position::all();
        return view('employer.index_eliminated', compact('employers','positions'));
    }

    public function store(StoreEmployerRequest $request)
    {
        try {
            DB::beginTransaction();

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

}
