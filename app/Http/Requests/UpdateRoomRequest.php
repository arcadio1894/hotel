<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
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
        $roomId = $this->route('room');
        return [
            'room_type' => 'required',
            'level' => 'required|integer',
            'number' => 'required|unique:rooms,number,' . $roomId,
            'description' => 'nullable',
            'status' => 'required',
            'image' => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'room_type.required' => 'El campo :attribute es obligatorio.',
            'level.required' => 'El campo :attribute es obligatorio.',
            'level.integer' => 'El campo :attribute debe ser un número entero.',
            'number.required' => 'El campo :attribute es obligatorio.',
            'number.unique' => 'Ya existe una habitación con este :attribute.',
            'description.nullable' => 'El campo :attribute debe ser nulo o una cadena de texto.',
            'status.required' => 'El campo :attribute es obligatorio.',
            'image.image' => 'El campo :attribute debe contener una imagen válida.',
        ];
    }

    public function attributes()
    {
        return [
            'room_type' => 'tipo de habitación',
            'level' => 'piso',
            'number' => 'número de habitación',
            'description' => 'descripción',
            'status' => 'estado',
            'image' => 'imagen',
        ];
    }
}
