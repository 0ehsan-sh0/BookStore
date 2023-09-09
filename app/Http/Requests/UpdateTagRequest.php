<?php

namespace App\Http\Requests;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTagRequest extends FormRequest
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
        $tag = Tag::find($this->route('tag'));
        if (!$tag) return ['tag' => 'required|exists:tags,id'];
        return [
            'url' => 'required|regex:/^[a-zA-Z0-9-]+$/|unique:main_categories,url,'. $tag->id,
            'name' => 'required'
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
            'url.required' => 'مسیر تگ الزامی است',
            'url.regex' => 'لطفا مسیر معتبر وارد کنید',
            'url.unique' => 'مسیر تگ نمیتواند تکراری باشد',
            'name.required' => 'نام تگ الزامی است',
            'tag.exists' => 'تگ مورد نظر یافت نشد',
            'tag.required' => 'تگ مورد نظر یافت نشد',
        ];
    }
}
