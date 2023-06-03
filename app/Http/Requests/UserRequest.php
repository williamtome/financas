<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:15',
        ];

        if ($this->method() === 'PUT') {
            $rules['email'] = 'sometimes|email|max:255';
            $rules['password'] = 'sometimes|min:6|max:15';
            return $rules;
        }

        return $rules;
    }
}
