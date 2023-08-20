<?php

namespace App\Http\Requests;

use App\Models\Translator;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTranslatorRequest extends FormRequest
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
        $translator = Translator::find($this->route('translator'));
        if (!$translator) return ['translator' => 'required|exists:translators,id'];
        return [
            'name' => 'required|unique:translators,name,' . $translator->id,
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
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
            'name.required' => 'نام مترجم الزامی است',
            'name.unique' => 'نام مترجم نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد',
            'translator.exists' => 'مسیر مورد نظر معتبر نیست',
            'translator.required' => 'مسیر مورد نظر معتبر نیست'
        ];
    }
}
