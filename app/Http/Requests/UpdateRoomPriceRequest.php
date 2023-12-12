<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomPriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'room_type' => 'required|exists:room_types,id',
            'season' => 'nullable|exists:seasons,id',
            'duration_hours' => 'required|numeric',
            'price' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'room_type.required' => 'El campo :attribute es obligatorio.',
            'room_type.exists' => 'El :attribute seleccionado no existe en la base de datos.',

            'season.exists' => 'El :attribute seleccionado no existe en la base de datos.',
            'duration_hours.required' => 'El campo :attribute es obligatorio.',
            'duration_hours.numeric' => 'El campo :attribute debe ser un número.',
            'price.required' => 'El campo :attribute es obligatorio.',
            'price.numeric' => 'El campo :attribute debe ser un número.',
        ];
    }

    public function attributes()
    {
        return [
            'room_type' => 'tipo de habitación',
            'season' => 'temporada',
            'duration_hours' => 'horas de duración',
            'price' => 'precio',
        ];
    }
}
