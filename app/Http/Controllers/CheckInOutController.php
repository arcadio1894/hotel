<?php

namespace App\Http\Controllers;

use App\Models\CheckInOut;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class CheckInOutController extends Controller
{
    public function cancelReservation($reservation_id){
        try {
            DB::beginTransaction();
            //dump($reservation_id);
            //dd($reservation_id);
            $reservation = Reservation::findOrFail($reservation_id);
            $reservation->update(['status_id' => 4]);
            DB::commit();

            return response()->json(['message' => 'Reserva Cancelada']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al cancelar Reserva. Detalles: ' . $e->getMessage(), 'customError' => true], 500);
        }
    }

    public function confirmCheckin($reservation_id){
        try {
            DB::beginTransaction();

            $checkinDate = request('checkinDate');
            $checkoutDate = request('checkoutDate');
            //dump($checkinDate);
            //dd($checkoutDate);
            $checkinout = new CheckInOut();
            $checkinout -> reservation_id = $reservation_id;
            $checkinout -> checkin_date = $checkinDate;
            $checkinout -> save();

            $reservation = Reservation::findOrFail($reservation_id);
            $reservation->update(['status_id' => 2]);
            DB::commit();

            return response()->json(['message' => 'Checkin confirmado']);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al confirmar Checkin. Detalles: ' . $e->getMessage(), 'customError' => true], 500);
        }
    }

    public function confirmCheckout($reservation_id){

        try {
            DB::beginTransaction();


            $checkinout = CheckInOut::where('reservation_id', $reservation_id)->first();
            $checkinDate = request('checkinDate');
            $checkoutDate = request('checkoutDate');

            $checkinout -> update([
                'checkout_date' => $checkoutDate
            ]);
            //dump($reservation_id);
            //dd($reservation_id);
            $reservation = Reservation::findOrFail($reservation_id);
            $reservation->update(['status_id' => 3]);
            DB::commit();

            return response()->json(['message' => 'Checkout confirmado']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' => 'Error al confirmar Checkout. Detalles: ' . $e->getMessage(), 'customError' => true], 500);
        }
    }

}
