<?php

namespace App\Http\Requests;

use App\Models\Writer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class UpdateWriterRequest extends FormRequest
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
        $writer = Writer::find($this->route('writer'));
        if (!$writer) return ['writer' => 'required|exists:main_categories,id'];
        return [
            'name' => 'required|unique:writers,name,' . $writer->id,
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
            'name.required' => 'نام نویسنده الزامی است',
            'name.unique' => 'نام نویسنده نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد',
            'writer.exists' => 'مسیر مورد نظر معتبر نیست',
            'writer.required' => 'مسیر مورد نظر معتبر نیست'
        ];
    }
}
