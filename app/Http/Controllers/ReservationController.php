<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetDataRoomsRequest;
use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;
use App\Models\Customer;

use App\Models\RoomType;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{

    function indexReservations(){
        $tipo='lista';
        $paymethods = DB::table('paymethods')->get();
        $documentTypes = DB::table('document_types')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        $states=  DB::table('statuses')->get();
        return view('reservation.indexReservations', compact('tipo','paymethods','user', 'documentTypes', 'states'));

    }

    public function getDataReservations(Request $request, $pageNumber = 1)
    {
        $perPage = 12;

        $documentCliente = $request->input('document_cliente');
        $name = $request->input('name');
        $documentType = $request->input('type');
        $status = $request->input('idle');
        $tipo = $request->input('tipo');
        //dump($request);
        //dd($request);
        if($tipo=='lista'){
            $query = Reservation::orderBy('id','DESC');
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

        if ($documentCliente) {
            $query->whereHas('customer', function ($query) use ($documentCliente) {
                $query->where('document', $documentCliente);
            });
        }

        if ($name) {
            $query->whereHas('customer', function ($query) use ($name) {
                $query->where('name', $name);
            });
        }

        if ($documentType) {
            $query->whereHas('customer', function ($query) use ($documentType) {
                $query->where('document_type', $documentType);
            });
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
                "code" => $operation->code,
                "customer_id" => $operation->customer_id,
                "customer_name" => $operation->customer->name,
                "customer_lastname" => $operation->customer->lastname,
                "employer_id" => $operation->employer_id,
                "employer_name" => $operation->employer->name,
                "employer_lastname" => $operation->employer->lastname,
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

    public function storeReservations(StoreReservationRequest $request)
    {
        try {

            DB::beginTransaction();
            $reservacion = new Reservation();
            $reservacion->code = $request->input('code');
            if ($request->input('idCustomer')) {
                $reservacion->customer_id = $request->input('idCustomer');
            } else {
                $customer = new Customer();
                $customer->document_type = $request->input('documentType');
                $customer->document = $request->input('document');
                $customer->name = $request->input('name');
                $customer->lastname = $request->input('lastname', null);
                $customer->phone = $request->input('phone');
                $customer->email = $request->input('email');
                $customer->birth = $request->input('birth');
                $customer->address = $request->input('address');
                $customer->save();
                $reservacion->customer_id = $customer->id;
            }

            $reservationType = $request->input('reservationType');

            if ($reservationType == 1) {
                $selectedDate = $request->input('selectedDate');
                $startTime = $request->input('selectedStartTime');
                $hoursQuantity = $request->input('hoursQuantity');
                $startDateTime = Carbon::parse($selectedDate . ' ' . $startTime);
                $endDateTime = $startDateTime->copy()->addHours($hoursQuantity);
            } else if ($reservationType == 2) {
                $startDate = $request->input('startDate');
                $endDate = $request->input('endDate');
                $startTime = $request->input('startTime');
                $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
                $endDateTime = Carbon::parse($endDate . ' 12:00:00');
            }

            $reservacion->employer_id = $request->input('employeerid');
            $reservacion->start_date = $startDateTime;
            $reservacion->end_date = $endDateTime;
            $reservacion->total_guest = $request->input('total_guest');
            $reservacion->paymethod_id = $request->input('paymethod');
            $reservacion->initial_pay = $request->input('initialpay');
            $reservacion->status_id = 1;
            $reservacion->save();
            $selectedRooms = $request->input('selectedRooms');
            foreach ($selectedRooms as $selectedRoom) {
                $detailReservation = new ReservationDetail();
                $detailReservation->reservation_id = $reservacion->id;
                $detailReservation->room_id = $selectedRoom;
                $detailReservation->save();
            }
            DB::commit();
            return response()->json(['success' => 'Reservación creada con éxito']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al crear La reservación. Detalles: ' . $e->getMessage(), 'customError' => true], 500);
        }
    }

    public function listAssignRooms($reservation_id){
        $tipo = 'listaAsignaCuartos';
        $room_types = DB::table('room_types')->get();
        $paymethods = DB::table('paymethods')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        return view('reservation.index', compact('tipo','room_types','paymethods','user','reservation_id'));
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
        $reservation_id = $request->input('reservation_id');
        $dateSearch = $request->input('dateSearch');
        //dump($request);
        //dd($request);
        if($tipo=='lista'){
            $query = Room::join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                        ->select('rooms.id', 'rooms.room_type_id', 'room_types.name', 'rooms.level', 'rooms.number', 'rooms.status')
                        ->orderBy('level','ASC');
            if ($type) {
                $query->where('room_type_id', $type);
            }
            if ($status) {
                $query->where('status', $status);
            }
            if($dateSearch and $status=='R'){
                $reservations  = Reservation::whereDate('start_date', '<=', $dateSearch)
                ->whereDate('end_date', '>=', $dateSearch)->orderBy('id','ASC')->pluck('id')->toArray();

                foreach($reservations as $reservation){
                    $reservation_details = ReservationDetail::where('reservation_id',$reservation)->pluck('room_id')->toArray();
                    $query=Room::whereIn('id', $reservation_details);
                    //dump($query);
                    //dd($query);
                }

            }
            /*if($dateSearch and $status=='O'){
                $reservations  = Reservation::whereDate('start_date', '<=', $dateSearch)
                ->whereDate('end_date', '>=', $dateSearch)->orderBy('id','ASC')->pluck('id')->toArray();

                foreach($reservations as $reservation){
                    $reservation_details = ReservationDetail::where('reservation_id',$reservation)->pluck('room_id')->toArray();
                    $query=Room::whereIn('id', $reservation_details);
                    //dump($query);
                    //dd($query);
                }
                if ($status) {
                    $query->where('status', $status);
                }

            }
            */

            
        }
        elseif($tipo=='listaAsignaCuartos'){
            $room_indexs = ReservationDetail::where('reservation_id', $reservation_id)->pluck('room_id')->toArray();

            $queryE = Room::whereIn('id', $room_indexs)->orderBy('level', 'ASC');
            
            $queryD = Room::where('status', 'D');
            if ($type) {
                $queryD->where('room_type_id', $type);
            }
            $queryD = $queryD->orderBy('level', 'ASC');
            
            $query = $queryE->union($queryD);
        }
        //dump($query);
        //dd($query);


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

    public function create(){
        $tipo='lista';
        $paymethods = DB::table('paymethods')->get();
        $documentTypes = DB::table('document_types')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        $states=  DB::table('statuses')->get();

        $roomTypes=RoomType::orderBy('capacity')->get();
        return view('reservation.create', compact('tipo','paymethods','user', 'documentTypes', 'states', 'roomTypes'));

    }


    function limitarTexto($texto, $longitud = 50) {
        if (strlen($texto) > $longitud) {
            $texto = substr($texto, 0, $longitud - 3) . '...';
        }
        return $texto;
    }

    public function getDataRooms(GetDataRoomsRequest $request, $pageNumber = 1){
        $perPage = 9;

        $reservationType = $request->input('reservationType');
        $total_guest= $request->input('total_guest');
        $selectRoomType= $request->input('selectRoomType');

        if ($reservationType==1){
            $selectedDate = $request->input('selectedDate');
            $startTime= $request->input('selectedStartTime');
            $hoursQuantity= $request->input('hoursQuantity');
            $startDateTime = Carbon::parse($selectedDate . ' ' . $startTime);
            $endDateTime = $startDateTime->copy()->addHours($hoursQuantity);
        }else{
            $startDate= $request->input('startDate');
            $endDate= $request->input('endDate');
            $startTime= $request->input('startTime');
            $startDateTime = Carbon::parse($startDate . ' ' . $startTime);
            $endDateTime = Carbon::parse($endDate . ' 12:00:00');
        }

        $occupiedRooms = ReservationDetail::whereHas('reservation', function ($query) use ($startDateTime, $endDateTime) {
            $query->where(function ($q) use ($startDateTime, $endDateTime) {
                $q->whereBetween('start_date', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                    ->orWhere(function ($qq) use ($startDateTime, $endDateTime) {
                        $qq->where('start_date', '<', $startDateTime)
                            ->where('end_date', '>', $endDateTime);
                    });
            });
        })->pluck('room_id')->toArray();

        if($selectRoomType){
            $roomsInitial=Room::where('room_type_id', $selectRoomType)->get();
        }
        else{
            $roomsInitial=Room::all();
        }

        $availableRooms = $roomsInitial->reject(function ($room) use ($occupiedRooms) {
            return in_array($room->id, $occupiedRooms);
        });

        $season = Season::whereBetween('start_date', [$startDateTime, $endDateTime])
            ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
            ->orWhere(function ($qq) use ($startDateTime, $endDateTime) {
                $qq->where('start_date', '<=', $startDateTime)
                    ->where('end_date', '>=', $endDateTime);
            })->first();

        $sortedRooms = $availableRooms->sortBy([
            ['level', 'asc'],
            ['roomType.capacity', 'desc'],
        ]);
        $selectedRooms = collect();
        $remainingGuests = $total_guest;

        foreach ($sortedRooms as $room) {
            if ($remainingGuests > 0) {
                $selectedRooms->push($room);
                $remainingGuests -= $room->roomType->capacity;
            } else {
                break;
            }
        }

        $otherRooms = $sortedRooms->diff($selectedRooms);

        $filteredRooms = $selectedRooms->merge($otherRooms);


        $totalFilteredRecords = $filteredRooms->count();
        $totalPages = ceil($totalFilteredRecords / $perPage);
        $startRecord = ($pageNumber - 1) * $perPage + 1;
        $endRecord = min($totalFilteredRecords, $pageNumber * $perPage);

        $rooms = $filteredRooms->skip(($pageNumber - 1) * $perPage)
            ->take($perPage);

        $arrayRooms = [];

        foreach ( $rooms as $room )
        {
            $price = null;

            if ($reservationType == 1 || $reservationType == 2) {
                $priceQuery = $room->roomPrices()
                    ->when($season, function ($query) use ($season, $reservationType) {
                        $query->where('season_id', $season->id)
                            ->where('duration_hours', $reservationType == 1 ? 1 : 24);
                    })
                    ->when(!$season, function ($query) use ($reservationType) {
                        $query->whereNull('season_id')
                            ->where('duration_hours', $reservationType == 1 ? 1 : 24);
                    });

                $price = $priceQuery->exists() ? $priceQuery->first()->price : null;
            }
            array_push($arrayRooms, [
                "id" => $room->id,
                "type_room_id" => $room->roomType->id,
                "type_room" => $room->roomType->name,
                "level" => $room->level,
                "number" => $room->number,
                "description" => $room->description,
                "description_short" => $this->limitarTexto($room->description),
                "image" => $room->image,
                "status" => $room->status,
                "capacity"=>$room->roomType->capacity,
                "price"=>$price,
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

        return ['data' => $arrayRooms, 'pagination' => $pagination];
    }

    public function generarCosto(Request $request) {
        $reservationType = $request->input('reservationType');
        $selectedRoomPrices = $request->input('selectedRoomPrices');
        $selectedRoom_ids = $request->input('selectedRooms');
        $selectedRooms=Room::whereIn('id', $selectedRoom_ids)->get();

        $totalCost = 0;
        $detalleReserva = [];

        if ($reservationType == 1) {
            $hoursQuantity = $request->input('hoursQuantity');
            foreach ($selectedRoomPrices as $index => $roomPrice) {
                $totalCost += $roomPrice * $hoursQuantity;

                $detalleReserva[] = [
                    'habitacion' => $selectedRooms[$index]->roomType->name . ' - ' .$selectedRooms[$index]->level.$selectedRooms[$index]->number,
                    'precioTotal' => $roomPrice * $hoursQuantity,
                ];
            }
        } else {
            $startDate = Carbon::parse($request->input('startDate'));
            $endDate = Carbon::parse($request->input('endDate'));
            $diffInDays = $startDate->diffInDays($endDate);

            foreach ($selectedRoomPrices as $index => $roomPrice) {
                $totalCost += $roomPrice * $diffInDays;

                // Agregar detalle de reserva para cada habitación
                $detalleReserva[] = [
                    'habitacion' => $selectedRooms[$index]->roomType->name . ' - ' .$selectedRooms[$index]->level.$selectedRooms[$index]->number,
                    'precioTotal' => $roomPrice * $diffInDays,
                ];
            }
        }

        return response()->json(['costoTotal' => $totalCost, 'detalleReserva' => $detalleReserva]);
    }

    public function edit($id){
        $reserva = Reservation::findOrFail($id);
        $startDate = Carbon::parse($reserva->start_date)->startOfDay();
        $endDate = Carbon::parse($reserva->end_date)->startOfDay();
        $reserva->reservationType = $startDate->diffInDays($endDate) < 1 ? 1 : 2;
        $reserva->start_date = Carbon::parse($reserva->start_date);
        $tipo='lista';
        $paymethods = DB::table('paymethods')->get();
        $documentTypes = DB::table('document_types')->get();
        $usuario = Auth::user();
        $user = (object)[
            "id" => $usuario->id,
            "name" => $usuario->name,
        ];
        $states=  DB::table('statuses')->get();

        $roomTypes=RoomType::orderBy('capacity')->get();
        return view('reservation.update', compact('tipo','paymethods','user', 'documentTypes', 'states', 'roomTypes', 'reserva', 'startDate', 'endDate'));

    }

    public function update(Request $request, $id)
    {
        try {

            return response()->json(['success' => 'Reservación actualizada con éxito']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la reservación. Detalles: ' . $e->getMessage(), 'customError' => true], 500);
        }
    }


}
