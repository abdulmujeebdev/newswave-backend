<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

abstract class APIRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        $response = response()->json([
            'success' => false,
            'errors' => $errors->messages(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
        throw new HttpResponseException($response);
    }

    protected function failedAuthorization()
    {
        $response = response()->json([
            'success' => false,
            'errors' => [],
            'message' => 'You are not authorized for this action'
        ], Response::HTTP_UNAUTHORIZED);
        throw new HttpResponseException($response);
    }
}
