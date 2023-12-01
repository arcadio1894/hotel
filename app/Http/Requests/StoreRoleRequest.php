<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|unique:roles,name','max:150',
            'description' => 'required','max:150',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'description' => 'descripción',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El :attribute es obligatorio.',
            'name.unique' => 'El :attribute ya se registró anteriormente',
            'name.max' => 'El :attribute es muy largo (máximo :max caracteres).',
            'description.required' => 'La :attribute es obligatoria.',
            'description.max' => 'La :attribute es muy larga (máximo :max caracteres).',
        ];
    }
}
