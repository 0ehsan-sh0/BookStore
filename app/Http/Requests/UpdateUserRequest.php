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
        $user = User::find($this->route('user'));
        if (!$user) return ['user' => 'required|exists:main_categories,id'];
        return [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|min:8',
            'password' => 'nullable|min:8|confirmed',
            'melicode' => 'required|iran_national_id|unique:users,melicode,'. $user->id,
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female',
            'state' => 'required',
            'city' => 'required',
            'role' => 'nullable|in:user,admin'
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
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'current_password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'melicode.required' => 'کد ملی الزامی است',
            'melicode.iran_national_id' => 'کد ملی را صحیح وارد کنید',
            'melicode.unique' => 'کد ملی قبلا در سایت ثبت شده است',
            'birthdate.required' => 'تاریخ تولد الزامی است',
            'birthdate.date' => 'لطفا تاریخ تولد را صحیح وارد کنید',
            'gender.required' => 'لطفا جنسیت را وارد کنید',
            'gender.in' => 'لطفا جنسیت را صحیح وارد کنید',
            'state.required' => 'لطفا استان را وارد کنید',
            'city.required' => 'لطفا شهر را وارد کنید',
            'role.in' => 'لطفا سطح دسترسی را درست وارد کنید',
            'user.exists' => 'مسیر مورد نظر معتبر نیست',
            'user.required' => 'مسیر مورد نظر معتبر نیست'
        ];
    }
}
