<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
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
            'document_type' => 'required',
            'document' => 'required|numeric|digits_between:8,12',
            'name' => 'required|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'phone' => 'required|numeric|digits_between:9,9',
            'email' => 'required|email|unique:customers|max:255',
            'birth' => 'required|date',
            'address' => 'required|string|min:8|max:255',

        ];
    }
    public function messages()
    {
        return [
            'document_type.required' => 'El campo tipo de documento es obligatorio.',
            'document.required' => 'El campo documento es obligatorio.',
            'document.numeric' => 'El campo documento debe ser un número.',
            'document.digits_between' => 'El campo documento debe tener entre :min y :max dígitos.',
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no puede tener más de :max caracteres.',
            'lastname.string' => 'El campo apellidos debe ser una cadena de texto.',
            'lastname.max' => 'El campo apellidos no puede tener más de :max caracteres.',
            'phone.required' => 'El campo teléfono es obligatorio.',
            'phone.numeric' => 'El campo teléfono debe ser un número.',
            'phone.digits_between' => 'El campo phone debe tener entre :min y :max dígitos.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Ingrese una dirección de correo electrónico válida.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'email.max' => 'El campo correo electrónico no puede tener más de :max caracteres.',
            'birth.required' => 'El campo fecha de nacimiento es obligatorio.',
            'birth.date' => 'El campo fecha de nacimiento debe ser una fecha válida.',
            'address.required' => 'El campo dirección es obligatorio.',
            'address.string' => 'El campo dirección debe ser una cadena de texto.',
            'address.min' => 'El campo dirección debe tener al menos :min caracteres.',
            'address.max' => 'El campo dirección no puede tener más de :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'document_type' => 'tipo de documento',
            'document' => 'documento',
            'name' => 'nombre',
            'lastname' => 'apellidos',
            'phone' => 'teléfono',
            'email' => 'correo electrónico',
            'birth' => 'fecha de nacimiento',
            'address' => 'dirección',
        ];
    }
}
