<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePermissionRequest extends FormRequest
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

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')->ignore($this->get('permission_id')),
            ],
            'description' => 'required|string|max:255',

        ];
    }

    public function messages()
    {
        return [

            'name.required'=> 'El :attribute es obligatorio.',
            'name.string'=> 'El :attribute debe contener caracteres válidos.',
            'name.max'=> 'El :attribute debe contener máximo 255 caracteres.',
            'name.unique'=> 'El :attribute debe ser único.',

            'description.required'=> 'El :attribute es obligatorio.',
            'description.string'=> 'El :attribute debe contener caracteres válidos.',
            'description.max'=> 'El :attribute debe contener máximo 255 caracteres.',

        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre',
            'Description' => 'Descripción',
        ];
    }
}
