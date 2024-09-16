<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OperatorOtpVerifyRequest extends FormRequest
{
    public function __construct(Request $request)
    {
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
        $rules = [
            'otp' => 'required|min:4|max:4',
            'phone_number' => 'required|min:10|max:10|exists:users,phone_number'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'otp.required' => 'The :attribute field is required.',
            'otp.min' => 'The :attribute must be at least :min characters.',
            'otp.max' => 'The :attribute must be at most :max characters.',
            'phone_number' => 'The :attribute field is required.',
            'phone_number.min' => 'The :attribute must be at least :min characters.',
            'phone_number.max' => 'The :attribute must be at most :max characters.',
            'phone_number.exists' => 'The :attribute Number does not exists in our system.'
        ];
        return $messages;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
            'status' => 'failure'
        ], Response::HTTP_BAD_REQUEST));
    }
}