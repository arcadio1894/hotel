<?php

namespace App\Http\Controllers;

use App\Models\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Exports\ReportCustomerExport;

class CustomerController extends Controller
{
    function index(){
        $customers = Customer::orderBy('id')->get();
        $title="Clientes";
        $lista=true;
        $report=false;
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index', compact('customers', "title",'lista','document_types','report'));
    }
    public function showDeletes(){
        $customers = Customer::onlyTrashed()->orderBy('deleted_at')->get();
        $title="Clientes Eliminados";
        $lista=false;
        $report=false;
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index', compact('customers', 'title','lista','document_types','report'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $customer = new Customer();
            $customer->document_type = $request->input('document_type');
            $customer->document = $request->input('document');
            $customer->name = $request->input('name');
            $customer->lastname = $request->input('lastname');
            $customer->phone = $request->input('phone');
            $customer->email = $request->input('email');
            $customer->birth = $request->input('birth');
            $customer->address = $request->input('address');

            $customer->save();
            DB::commit();
            return response()->json(['success' => 'Cliente creado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear el Cliente. Detalles: ' . $e->getMessage()], 500);
        }
    }


    public function update(Request $request, Customer $customer)
    {
        try {
            DB::beginTransaction();
            $customer = Customer::find($request->input('id'));
            $customer->update([
                'document_type' => $request->input('document_type'),
                'document' => $request->input('document'),
                'name' => $request->input('name'),
                'lastname' => $request->input('lastname'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'birth' => $request->input('birth'),
                'address' => $request->input('address'),
            ]);
            DB::commit();
            return response()->json(['success' => 'Cliente actualizado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al actualizar el Cliente. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $customer = Customer::find($id);

            if (!$customer) {
                return response()->json(['message' => 'El Cliente no existe'], 404);
            }
            $customer->delete();
            DB::commit();
            return response()->json(['message' => 'Cliente eliminado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al eliminar el Cliente. Detalles: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        try {
            DB::beginTransaction();
            $customer = Customer::onlyTrashed()->find($id);

            if (!$customer) {
                return response()->json(['message' => 'El Cliente no existe'], 404);
            }
            $customer->restore();
            DB::commit();
            return response()->json(['message' => 'Cliente restaurado correctamente']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al restaurar el Cliente. Detalles: ' . $e->getMessage()], 500);
        }
    }

    function report(){
        $customers = Customer::withTrashed()->get();
        $title="Clientes";
        $lista=true;
        $report=true;
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index', compact('customers', "title",'lista','document_types', 'report'));
    }

    public function generateReport()
    {
        $customers = DB::table('customers')->whereNull('deleted_at')->get();
        $deletedCustomers = DB::table('customers')->whereNotNull('deleted_at')->get();

        $data = [];
        $deletedData = [];

        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->id,
                'document_type' => $customer->document_type,
                'document' => $customer->document,
                'name' => $customer->name,
                'lastname' => $customer->lastname,
                'phone' => $customer->phone,
                'email' => $customer->email,
                'birth' => $customer->birth,
                'address' => $customer->address,
            ];
        }

        foreach ($deletedCustomers as $deletedCustomer) {
            $deletedData[] = [
                'id' => $deletedCustomer->id,
                'document_type' => $deletedCustomer->document_type,
                'document' => $deletedCustomer->document,
                'name' => $deletedCustomer->name,
                'lastname' => $deletedCustomer->lastname,
                'phone' => $deletedCustomer->phone,
                'email' => $deletedCustomer->email,
                'birth' => $deletedCustomer->birth,
                'address' => $deletedCustomer->address,
            ];
        }

        return Excel::download(new ReportCustomerExport($data,$deletedData), 'ReporteDeClientes.xlsx');
    }


}
