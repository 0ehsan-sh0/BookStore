<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

class StoreCommentRequest extends FormRequest
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
        $rules = [
            'comment' => 'required',
        ];
        $hasBook = $this->book_id;
        $hasArticle = $this->article_id;
        if ($hasBook) {
            $rules['book_id'] = 'required|exists:books,id';
            $this->article_id = null;
        } elseif ($hasArticle) {
            $rules['article_id'] = 'required|exists:articles,id';
        } else {
            $rules['empty'] = 'required';
        }

        return $rules;
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
            'comment.required' => 'لطفا نظرت رو بنویس',
            'book_id.required' => 'کتابی که میخوای براش نظر بدی رو انتخاب کن',
            'book_id.exists' => 'کتاب مورد نظر یافت نشد',
            'article_id.required' => 'مقاله ای که میخوای براش نظر بدی رو انتخاب کن',
            'article_id.exists' => 'مقاله مورد نظر یافت نشد',
            'empty.required' => 'خطا',
        ];
    }
}
