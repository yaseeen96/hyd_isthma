<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MemberLoginRequest extends FormRequest
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
        if($this->inputType === 'phone') {
            return [
            'phone' => 'required|min:10|max:10|exists:members,phone'
        ];
        } else {
            return [
            'phone' => 'required|email|exists:members,email'
            ];
        }
    }

    public function messages()
    {
        if ($this->inputType === 'phone') {
            return [
                'phone.required' => 'The :attribute field is required.',
                'phone.min' => 'The :attribute must be at least :min characters.',
                'phone.max' => 'The :attribute must be at most :max characters.',
                'phone.exists' => 'The :attribute Number does not exists in our system.'
            ];
        } else {
            return [
                'phone.required' => 'The :attribute field is required.',
                'phone.email' => 'The email must be a valid email address.',
                'phone.exists' => 'The email does not exists in our system.'
            ];
        }
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => $validator->errors(),
            'status' => 'failure'
        ], Response::HTTP_BAD_REQUEST));
    }
}