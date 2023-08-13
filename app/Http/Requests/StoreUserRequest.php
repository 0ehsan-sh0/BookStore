<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'error' => 'error',
                'message' => 'لطفا خطاهای زیر را بررسی کنید',
                'errors' => $validator->errors(),
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

    public function messages(): array
    {
        return [
            'name.required' => 'فیلد نام الزامی است',
            'lastname.required' => 'فیلد نام خانوادگی الزامی است',
            'email.required' => 'فیلد ایمیل الزامی است',
            'email.unique' => 'ایمیل مورد نظر قبلا ثبت شده است',
            'email.email' => 'لطفا ایمیل را صحیح وارد کنید',
            'password.required' => 'فیلد رمز عبور الزامی است',
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد'
        ];
    }
}
