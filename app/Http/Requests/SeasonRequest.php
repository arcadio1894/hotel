<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeasonRequest extends FormRequest
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
            'start_date' => 'required|date|date_format:Y-m-d',
            'end_date' => 'required|date|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    // Puedes agregar mensajes personalizados si lo deseas
    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string' => 'El :attribute debe ser una cadena de texto.',
            'name.max' => 'El :attribute no debe superar los :max caracteres.',

            'start_date.required' => 'La :attribute es obligatoria.',
            'start_date.date' => 'La :attribute debe ser una fecha válida.',
            'start_date.date_format' => 'La :attribute debe tener el formato Y-m-d.',

            'end_date.required' => 'La :attribute es obligatorio.',
            'end_date.date' => 'La :attribute debe ser una fecha válida.',
            'end_date.date_format' => ':attribute debe tener el formato Y-m-d.',
            'end_date.after_or_equal' => 'La :attribute debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'start_date' => 'fecha de inicio',
            'end_date' => 'fecha de fin',
        ];
    }

}
