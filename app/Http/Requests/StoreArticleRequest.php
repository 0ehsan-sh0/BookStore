<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreArticleRequest extends FormRequest
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
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:3072',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
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
            'title.required' => 'عنوان مقاله الزامی است',
            'subtitle.required' => 'خلاصه مقاله الزامی است',
            'description.required' => 'توضیحات مقاله الزامی است',
            'photo.mimes' => 'لطفا فایل با فرمت عکس وارد کنید',
            'photo.max' => 'حجم فایل نباید بیشتر از سه مگابایت باشد',
            'tags.required' => 'حداقل یک تگ الزامی است',
            'tags.*.exists' => 'تگ مورد نظر یافت نشد',
        ];
    }
}
