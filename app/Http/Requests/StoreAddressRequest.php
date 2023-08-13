<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreAddressRequest extends FormRequest
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
            'phone' => 'required|unique:addresses|numeric',
            'city' => 'required',
            'state' => 'required',
            'place_number' => 'required|unique:addresses|numeric',
            'post_code' => 'required|unique:addresses|numeric',
            'address' => 'required',
            'user_id' => 'required|exists:users,id'
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
            'name.required' => 'نام الزامی است',
            'lastname.required' => 'نام خانوادگی الزامی است',
            'state.required' => 'لطفا استان را وارد کنید',
            'city.required' => 'لطفا شهر را وارد کنید',
            'phone.required' => 'شماره همراه الزامی است',
            'phone.unique' => 'شماره همراه تکراری است',
            'phone.numeric' => 'شماره تلفن باید عددی باشد',
            'place_number.required' => 'شماره تلفن خانه الزامی است',
            'place_number.unique' => 'شماره تلفن خانه تکراری است',
            'place_number.numeric' => 'شماره تلفن خانه باید عددی باشد',
            'post_code.required' => 'کد پستی الزامی است',
            'post_code.unique' => 'کد پستی تکراری است',
            'post_code.numeric' => 'کد پستی باید عددی باشد',
            'address.required' => 'آدرس الزامی است',
            'user_id.required' => 'شناسه کاربر الزامی است',
            'user_id.exists' => 'کاربر مورد نظر وجود ندارد'
        ];
    }
}
