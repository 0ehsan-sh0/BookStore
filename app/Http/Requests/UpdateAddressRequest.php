<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateAddressRequest extends FormRequest
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
        $addressID = $this->route('address')->id;

        return [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|numeric|unique:addresses,phone,'.$addressID,
            'city' => 'required',
            'state' => 'required',
            'place_number' => 'required|numeric|unique:addresses,place_number,'.$addressID,
            'post_code' => 'required|numeric|unique:addresses,post_code,'.$addressID,
            'address' => 'required',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
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
            'address.exists' => 'مسیر مورد نظر معتبر نیست',
        ];
    }
}
