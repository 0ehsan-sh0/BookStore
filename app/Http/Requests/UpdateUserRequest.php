<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateUserRequest extends FormRequest
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
        $user = auth()->user();

        return [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'current_password' => 'nullable|min:8',
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'digits:11|unique:users,phone,'.$user->id,
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
            'name.required' => 'فیلد نام الزامی است',
            'lastname.required' => 'فیلد نام خانوادگی الزامی است',
            'email.required' => 'فیلد ایمیل الزامی است',
            'email.unique' => 'ایمیل مورد نظر قبلا ثبت شده است',
            'email.email' => 'لطفا ایمیل را صحیح وارد کنید',
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'current_password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'phone.unique' => 'شماره تلفن قبلا در سایت ثبت شده است',
            'phone.digits' => 'لطفا شماره تلفن معتبر وارد کنید',
        ];
    }
}
