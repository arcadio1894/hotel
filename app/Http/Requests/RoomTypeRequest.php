<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoomTypeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'capacity' => 'required|numeric',
        ];
    }

    // Puedes agregar mensajes personalizados si lo deseas
    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe ser una cadena de texto.',
            'name.max' => 'El :attribute no debe superar los :max caracteres.',

            'description.string' => 'El :attribute debe ser una cadena de texto.',
            'description.max' => 'El :attribute no debe superar los :max caracteres.',

            'capacity.required' => 'El campo :attribute es obligatorio.',
            'capacity.numeric' => 'El campo :attribute debe ser un valor numérico.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
            'capacity' => 'capacidad'
        ];
    }
}
