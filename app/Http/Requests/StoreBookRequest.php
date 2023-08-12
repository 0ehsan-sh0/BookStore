<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreBookRequest extends FormRequest
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
                'english_name' => 'nullable|regex:/^[a-zA-Z0-9 ]+$/',
                'description' => 'required',
                'price' => 'required|numeric|min:0',
                'photo' => 'required|mimes:jpg,jpeg,png|max:3072',
                'print_series' => 'required|numeric|min:0|max:65533',
                'isbn' => 'required|unique:books',
                'format' => 'required',
                'pages' => 'required|numeric|min:1',
                'publish_year' => 'required|numeric|min:1000',
                'count' => 'required|numeric|min:0',
                'writer_id' => 'required|exists:writers,id',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
                'translators' => 'required|array',
                'translators.*' => 'exists:translators,id'
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
            'name.required' => 'نام کتاب الزامی است',
            'english_name.regex' => 'لطفا نام انگلیسی را با حروف انگلیسی وارد کنید',
            'description.required' => 'توضیحات الزامی است',
            'price.required' => 'فیلد قیمت الزامی است',
            'price.numeric' => 'فیلد قیمت عددی است',
            'price.min' => 'نمیتوانید در این فیلد مقدار منفی وارد کنید',
            'photo.required' => 'عکس کتاب الزامی است',
            'photo.mimes' => 'فرمت عکس باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از سه مگابایت باشد',
            'print_series.required' => 'این فیلد الزامی است',
            'print_series.numeric' => 'این فیلد باید عددی باشد',
            'print_series.min' => 'نمیتوانید در این فیلد مقدار منفی وارد کنید',
            'print_series.max' => 'عدد وارد شده بزرگتر از حد مجاز است',
            'isbn.required' => 'شابک کتاب الزامی است',
            'isbn.unique' => 'شابک نباید تکراری باشد',
            'format.required' => 'این فیلد الزامی است',
            'pages.required' => 'این فیلد الزامی است',
            'pages.numeric' => 'این فیلد باید عددی باشد',
            'pages.min' => 'تعداد صفحات نمیتواند منفی یا صفر باشد',
            'publish_year.required' => 'این فیلد الزامی است',
            'publish_year.numeric' => 'سال انتشار باید به عدد نوشته شود',
            'publish_year.min' => 'لطفا سال را درست وارد کنید',
            'count.required' => 'این فیلد الزامی است',
            'count.numeric' => 'این فیلد باید عددی باشد',
            'count.min' => 'تعداد نباید کمتر از صفر وارد شود',
            'writer_id.required' => 'نویسنده باید برای کتاب تعریف شود',
            'writer_id.exists' => 'نویسنده مورد نظر یافت نشد',
            'categories.required' => 'حداقل یک دسته بندی الزامی است',
            'categories.*.exists' => 'دسته بندی مورد نظر یافت نشد',
            'translators.required' => 'حداقل یک مترجم الزامی است',
            'translators.*.exists' => 'مترجم مورد نظر یافت نشد'
        ];
    }
}
