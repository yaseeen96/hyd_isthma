<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OperatorLoginRequest extends FormRequest
{
    public function __construct(Request $request)
    {
        $input = $request->input('phone_number');
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone_number' => 'required|min:10|max:10|exists:users,phone_number'
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required' => 'The :attribute field is required.',
            'phone_number.min' => 'The :attribute must be at least :min characters.',
            'phone_number.max' => 'The :attribute must be at most :max characters.',
            'phone_number.exists' => 'The :attribute Number does not exists in our system.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->messages();
        throw new HttpResponseException(response()->json([
            'message' =>  $errors['phone_number'],
        ], Response::HTTP_BAD_REQUEST));
    }
}