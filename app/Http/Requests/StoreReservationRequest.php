<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(Request $request)
    {
        $customerId = $request->input('idCustomer');
        return [
            'code' => 'required|string',
            'idCustomer' => 'nullable|numeric',
            'documentType' => 'required_if:idCustomer,null|string',
            'document' => 'required_if:idCustomer,null|string|digits_between:8,12',
            'name' => 'required_if:idCustomer,null|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'phone' => 'required_if:idCustomer,null|numeric|digits_between:9,9',
            'email' => [
                'required_if:idCustomer,null',
                'email',
                Rule::unique('customers')->ignore($customerId)->where(function ($query) use ($customerId) {
                    return $customerId !== null;
                }),
                'max:255',
            ],
            'birth' => 'required_if:idCustomer,null|date',
            'address' => 'required_if:idCustomer,null|string|min:8|max:255',
            'employeerid' => 'required|numeric',
            'reservationType' => 'required|in:1,2',
            'hoursQuantity' => 'required_if:reservationType,1',
            'selectedDate' => 'required_if:reservationType,1|date',
            'startDate' => 'required_if:reservationType,2|date',
            'endDate' => 'required_if:reservationType,2|date|after_or_equal:start_date',
            'startTime' => 'required_if:reservationType,1|date_format:H:i',
            'selectedStartTime' => 'required_if:reservationType,2|date_format:H:i',
            'total_guest' => 'required|numeric',
            'paymethod' => 'required',
            'initialpay' => 'required|numeric',
            'selectedRooms' => 'required|array',
            'selectedRooms.*' => 'numeric',
        ];
    }

    public function messages()
    {
        return [
            'code.required' => 'Busque el cliente para generar el :attribute.',
            'documentType.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'document.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'document.digits_between' => 'El campo :attribute debe estar entre 8 a 12 dígitos.',
            'name.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'phone.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'email.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'birth.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'address.required_if' => 'El campo :attribute es obligatorio al crear un nuevo cliente.',
            'reservationType.required' => 'El campo :attribute es obligatorio.',
            'reservationType.in' => 'El campo :attribute debe ser 1 o 2.',
            'hoursQuantity.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por hora.',
            'hoursQuantity.numeric' => 'El valor de la :attribute debe ser un número.',
            'selectedDate.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por hora.',
            'startDate.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por día.',
            'endDate.after_or_equal' => 'La :attribute debe ser mayor o igual que la fecha de inicio.',
            'endDate.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por día.',
            'startTime.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por hora.',
            'selectedStartTime.required_if' => 'El campo :attribute es obligatorio para el tipo de reserva por día.',
            'total_guest.required' => 'El campo :attribute es obligatorio.',
            'paymethod.required' => 'El campo :attribute es obligatorio.',
            'initialpay.required' => 'El campo :attribute es obligatorio.',
            'selectedRooms.required' => 'El campo :attribute es obligatorio.',
            'selectedRooms.*.numeric' => 'El valor de la :attribute no es válido.',
        ];
    }

    public function attributes()
    {
        return [
            'code' => 'Código de reserva',
            'idCustomer' => 'ID del Cliente',
            'documentType' => 'Tipo de Documento',
            'document' => 'Documento',
            'name' => 'Nombre',
            'lastname' => 'Apellido',
            'phone' => 'Teléfono',
            'email' => 'Correo Electrónico',
            'birth' => 'Fecha de Nacimiento',
            'address' => 'Dirección',
            'employeerid' => 'ID del Empleado',
            'reservationType' => 'Tipo de Reserva',
            'hoursQuantity' => 'Cantidad de Horas',
            'selectedDate' => 'Fecha Seleccionada',
            'startDate' => 'Fecha de Inicio',
            'endDate' => 'Fecha de Fin',
            'startTime' => 'Hora de Inicio',
            'selectedStartTime' => 'Hora de Inicio Seleccionada',
            'total_guest' => 'Número Total de Invitados',
            'paymethod' => 'Método de Pago',
            'initialpay' => 'Pago Inicial',
            'selectedRooms' => 'Habitaciones Seleccionadas',
            'selectedRooms.*' => 'Habitación Seleccionada',
        ];
    }
}
