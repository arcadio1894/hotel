<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployerRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'position_id' => 'required|integer|exists:positions,id',
            'dni' => 'required|numeric|regex:/^[0-9]{8,9}$/',
            'address' => 'required|string|max:255|nullable',
            'email' => 'required|string',
            'birth' => 'required|date',
            'phone' => 'required|numeric|min:9',

        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.string'=> 'El :attribute debe contener caracteres válidos.',
            'name.max'=> 'El :attribute debe contener máximo 255 caracteres.',
            'lastname.required' => 'El :attribute es obligatorio.',
            'lastname.string'=> 'El :attribute debe contener caracteres válidos.',
            'lastname.max'=> 'El :attribute debe contener máximo 255 caracteres.',
            'position_id.required' => 'El :attribute es obligatorio.',
            'position_id.exists' => 'El :attribute seleccionado no es válido.',
            'dni.required' => 'El :attribute es obligatorio.',
            'dni.numeric'=> 'El :attribute debe contener datos válidos.',
            'dni.regex'=> 'El :attribute debe contener entre 8 y 9 dígitos.',
            'address.required' => 'La :attribute es obligatorio.',
            'address.string'=> 'La :attribute debe contener caracteres válidos.',
            'address.max'=> 'La :attribute debe contener máximo 255 caracteres.',
            'email.required'=> 'El :attribute es obligatorio.',
            'birth.required'=> 'El :attribute es obligatorio.',
            'phone.required' => 'El :attribute es obligatorio.',
            'phone.numeric'=> 'El :attribute debe contener datos válidos.',
            'phone.min'=> 'El :attribute debe contener minimo 9 dígitos.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'lastname' => 'apellido',
            'position_id' => 'puesto',
            'dni' => 'DNI',
            'address' => 'dirección',
            'email' => 'correo electrónico',
            'birth' => 'fecha de nacimiento',
            'phone' => 'teléfono',
        ];
    }
}
