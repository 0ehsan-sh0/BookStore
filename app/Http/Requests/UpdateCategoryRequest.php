<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCategoryRequest extends FormRequest
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
        $category = Category::find($this->route('category'));
        if (!$category) return ['category' => 'required|exists:categories,id'];
        return [
            'url' => 'required|regex:/^[a-zA-Z0-9-]+$/|unique:categories,url,' . $category->id,
            'name' => 'required',
            'main_category_id' => 'required|exists:main_categories,id' // Add validation rule for main_category_id
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
            'url.required' => 'مسیر دسته بندی الزامی است',
            'name.required' => 'نام دسته بندی الزامی است',
            'url.regex' => 'لطفا مسیر معتبر وارد کنید',
            'main_category_id.required' => 'دسته بندی اصلی الزامی است', // Add custom error message for main_category_id
            'main_category_id.exists' => 'دسته بندی اصلی معتبر نیست', // Add custom error message for main_category_id
            'category.exists' => 'مسیر مورد نظر معتبر نیست',
            'category.required' => 'مسیر مورد نظر معتبر نیست'
        ];
    }
}
