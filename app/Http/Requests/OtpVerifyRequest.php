<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class OtpVerifyRequest extends FormRequest
{
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
            'phone' => 'required|min:10|max:10|exists:users,phone',
            'otp' => 'required|min:4|max:4',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'The :attribute field is required.',
            'phone.min' => 'The :attribute must be at least :min characters.',
            'phone.max' => 'The :attribute must be at most :max characters.',
            'phone.exists' => 'The :attribute Number does not exists in our system.',
            'otp.required' => 'The :attribute field is required.',
            'otp.min' => 'The :attribute must be at least :min characters.',
            'otp.max' => 'The :attribute must be at most :max characters.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
            'status' => 'failure'
        ], Response::HTTP_BAD_REQUEST));
    }
}