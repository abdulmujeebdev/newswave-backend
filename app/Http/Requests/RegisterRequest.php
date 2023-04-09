<?php

namespace App\Http\Requests;
class RegisterRequest extends APIRequest
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
        logger(request()->all());

        return [
            'name' => 'required|min:3|max:60',
            'email' => [
                'required',
                'min:6',
                'regex:/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i',
                'unique:users,email',
                'max:60'
            ],
            'password' => 'required:min:8|max:30',
        ];
    }
}
