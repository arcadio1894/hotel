<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GetDataRoomsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reservationType' => 'required|in:1,2',
            'total_guest' => 'required|numeric',
            'selectRoomType' => 'nullable|numeric',
            'selectedDate' => 'required_if:reservationType,1|date',
            'selectedStartTime' => 'required_if:reservationType,1|date_format:H:i',
            'hoursQuantity' => 'required_if:reservationType,1',
            'startDate' => 'required_if:reservationType,2|date',
            'endDate' => 'required_if:reservationType,2|date|after_or_equal:startDate',
            'startTime' => 'required_if:reservationType,2|date_format:H:i',
        ];
    }

    public function messages()
    {
        return [
            'reservationType.required' => 'El tipo de reserva es obligatorio.',
            'reservationType.in' => 'El tipo de reserva debe ser por hora o por día.',
            'total_guest.required' => 'El número total de personas es obligatorio.',
            'total_guest.numeric' => 'El número total de invitados debe ser un valor numérico.',
            'selectRoomType.numeric' => 'El tipo de habitación seleccionado debe ser un valor numérico.',
            'selectedDate.required_if' => 'La fecha seleccionada es obligatoria para el tipo de reserva por hora.',
            'selectedDate.date' => 'La fecha seleccionada debe ser una fecha válida.',
            'selectedStartTime.required_if' => 'La hora de inicio seleccionada es obligatoria para el tipo de reserva por.',
            'selectedStartTime.date_format' => 'La hora de inicio seleccionada debe tener el formato H:i.',
            'hoursQuantity.required_if' => 'La cantidad de horas es obligatoria para el tipo de reserva por hora.',
            'hoursQuantity.numeric' => 'La cantidad de horas debe ser un valor numérico.',
            'startDate.required_if' => 'La fecha de inicio es obligatoria para el tipo de reserva por día.',
            'startDate.date' => 'La fecha de inicio debe ser una fecha válida.',
            'endDate.required_if' => 'La fecha de fin es obligatoria para el tipo de reserva por día.',
            'endDate.date' => 'La fecha de fin debe ser una fecha válida.',
            'endDate.after_or_equal' => 'La fecha de fin debe ser mayor o igual que la fecha de inicio.',
            'endTime.required_if' => 'La hora de fin es obligatoria para el tipo de reserva por día.',
            'endTime.date_format' => 'La hora de fin debe tener el formato H:i.',
        ];
    }
}
