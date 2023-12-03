<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Customer;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{

    function indexReservations(){
        $tipo='lista';
        $paymethods = DB::table('paymethods')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        return view('reservation.indexReservations', compact('tipo','paymethods','user'));

    }

    public function getDataReservations(Request $request, $pageNumber = 1)
    {
        $perPage = 12;

        //$documentCliente = $request->input('document_cliente');
        //$name = $request->input('name');
        $type = $request->input('type');
        $status = $request->input('idle');
        $tipo = $request->input('tipo');
        //dump($request);
        //dd($request);
        if($tipo=='lista'){
            $query = Reservation::orderBy('id','ASC');
        }
        //dump($query);
        //dd($query);
        /*
        elseif($tipo=='eliminados'){
                $query = Room::onlyTrashed()->orderBy('id', 'DESC');
        }
        elseif ($tipo=='reporte'){
            $query = Room::withTrashed()->orderBy('id', 'DESC');
        }*/

        // Aplicar filtros si se proporcionan
        /*
        if ($documentCliente) {
            $query->where('document', $documentCliente);
        }

        if ($name) {
            $query->where('name', $name);
        }
        */

        if ($type) {
            $query->where('code', $type);
        }
        if ($status) {
            $query->where('customer_id', $status);
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
                "code" => $operation->code,
                "customer_id" => $operation->customer_id,
                "employer_id" => $operation->employer_id,
                "status_id" => $operation->status_id,
                "paymethod_id" => $operation->paymethod_id,
                "start_date" => $operation->start_date,
                "end_date" => $operation->end_date,
                "initial_pay" => $operation->initial_pay,
                "total_guest" => $operation->total_guest,

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

        public function storeReservations(Request $request)
        {
            //dump($request);
            //dd($request);
            $reservacion = new Reservation();
            $reservacion->code = $request->input('code');
            $reservacion->customer_id = $request->input('idCustomer');
            $reservacion->employer_id = $request->input('employeerid');
            $reservacion->start_date = $request->input('startdate');
            $reservacion->end_date = $request->input('startdate');
            $reservacion->total_guest = $request->input('totalguest');
    
            $reservacion->save();
    
            return response()->json(['success' => 'Reservación creada con éxito']);
        }


    function index(){
        $tipo='lista';
        $room_types = DB::table('room_types')->get()/*->pluck('name')->toArray()*/;
        $paymethods = DB::table('paymethods')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        return view('reservation.index', compact('tipo','room_types','paymethods','user'));
    }

    public function getDataReservation(Request $request, $pageNumber = 1)
    {
        $perPage = 12;

        //$documentCliente = $request->input('document_cliente');
        //$name = $request->input('name');
        $type = $request->input('type');
        $status = $request->input('idle');
        $tipo = $request->input('tipo');
        //dump($request);
        //dd($request);
        if($tipo=='lista'){
            $query = Room::join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                        ->select('rooms.id', 'rooms.room_type_id', 'room_types.name', 'rooms.level', 'rooms.number', 'rooms.status')
                        ->orderBy('level','ASC');
        }
        //dump($query);
        //dd($query);
        /*
        elseif($tipo=='eliminados'){
                $query = Room::onlyTrashed()->orderBy('id', 'DESC');
        }
        elseif ($tipo=='reporte'){
            $query = Room::withTrashed()->orderBy('id', 'DESC');
        }*/

        // Aplicar filtros si se proporcionan
        /*
        if ($documentCliente) {
            $query->where('document', $documentCliente);
        }

        if ($name) {
            $query->where('name', $name);
        }
        */

        if ($type) {
            $query->where('room_type_id', $type);
        }
        if ($status) {
            $query->where('status', $status);
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
                "room_type_id" => $operation->room_type_id,
                "room_type_name" => $operation->name,
                "level" => $operation->level,
                "number" => $operation->number,
                "status" => $operation->status,

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

        // Ejemplo de búsqueda de cliente en el controlador
    public function buscarCliente(Request $request)
    {
        $dni = $request->input('dni');
        $cliente = Customer::where('document', $dni)->first();

        // Consulta el último registro en la tabla reservations
        $ultimoRegistro = Reservation::latest('id')->first();

        // Inicializa el ID
        $nuevoId = 1;

        // Verifica si se encontró algún registro
        if ($ultimoRegistro) {
            // Incrementa el ID
            $nuevoId = $ultimoRegistro->id + 1;
        }

        // Genera el código con el formato especificado
        $codigo = 'RS-' . str_pad($nuevoId, 5, '0', STR_PAD_LEFT);

            // Agrupa las variables en un array asociativo
        $respuesta = [
            'cliente' => $cliente,
            'codigo' => $codigo,
        ];

        return response()->json($respuesta);
    }


}
