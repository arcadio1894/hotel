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
        $tipo='lista';
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index', compact('tipo','document_types'));
    }
    public function showDeletes(){
        $tipo='eliminados';
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index', compact('tipo','document_types'));
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
        $tipo = "reporte";
        $document_types=['DNI','PASAPORTE','CARNÉ DE EXTRANJERIA','LICENCIA DE CONDUCIR','CARNÉ DE ESTUDIANTE'];
        return view('customer.index',compact('tipo','document_types'));
    }


    public function getDataOperations(Request $request, $pageNumber = 1)
    {
        $perPage = 10;

        $documentCliente = $request->input('document_cliente');
        $name = $request->input('name');
        $type = $request->input('type');
        $tipo = $request->input('tipo');
        if($tipo=='lista'){
            $query = Customer::orderBy('id', 'DESC');
        }
        elseif($tipo=='eliminados'){
                $query = Customer::onlyTrashed()->orderBy('id', 'DESC');
        }
        elseif ($tipo=='reporte'){
            $query = Customer::withTrashed()->orderBy('id', 'DESC');
        }

        // Aplicar filtros si se proporcionan
        if ($documentCliente) {
            $query->where('document', $documentCliente);
        }

        if ($name) {
            $query->where('name', $name);
        }

        if ($type) {
            $query->where('document_type', $type);
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
                "document_type" => $operation->document_type,
                "document" => $operation->document,
                "name" => $operation->name,
                "lastname" => $operation->lastname,
                "phone" => $operation->phone,
                "email" => $operation->email,
                "birth" => $operation->birth,
                "address" => $operation->address,
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
