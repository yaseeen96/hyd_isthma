<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OtpVerifyRequest extends FormRequest
{
    private $inputType;
    public function __construct(Request $request)
    {
        $input = $request->input('phone');
        $this->inputType = str_contains($input, '@') ? 'email' : 'phone';
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
        ];
        if ($this->inputType === 'phone') {
            $rules['phone'] = 'required|min:10|max:10|exists:members,phone';
        } else {
            $rules['phone'] = 'required|email|exists:members,email';
        }
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'otp.required' => 'The :attribute field is required.',
            'otp.min' => 'The :attribute must be at least :min characters.',
            'otp.max' => 'The :attribute must be at most :max characters.',
        ];
        if($this->inputType === 'phone') {
            $messages['phone'] = 'The :attribute field is required.';
            $messages['phone.min'] = 'The :attribute must be at least :min characters.';
            $messages['phone.max'] = 'The :attribute must be at most :max characters.';
            $messages['phone.exists'] = 'The :attribute Number does not exists in our system.';
        } else {
            $messages['phone'] = 'The :attribute field is required.';
            $messages['phone.email'] = 'The email must be a valid email address.';
            $messages['phone.exists'] = 'The email does not exists in our system.';
        }
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