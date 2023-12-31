<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class StoreCartRequest extends FormRequest
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
        $userId = $this->user()->id;

        return [
            'ischeckedout' => 'boolean|required',
            'books' => 'required|array',
            'books.*' => 'exists:books,id',
            'counts' => 'required|array',
            'counts.*' => 'integer|min:1',
            'address_id' => [
                'required',
                Rule::exists('addresses', 'id')->where(function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                }),
            ],
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
            'ischeckedout.required' => 'لطفا وضعیت پرداخت را مشخص کنید',
            'ischeckedout.boolean' => 'لطفا مقدار را درست وارد کنید',
            'books.required' => 'حداقل یک کتاب الزامی است',
            'books.*.exists' => 'کتاب مورد نظر یافت نشد',
            'counts.required' => 'تعداد هر کتاب الزامی است',
            'counts.*.integer' => 'لطفا تعداد را درست وارد کنید',
            'counts.*.min' => 'حداقل تعداد خریداری شده از هر کتاب باید یک عدد باشد',
            'address_id.required' => 'لطفا آدرس مورد نظر را وادر نمایید',
            'address_id.exists' => 'آدرس مورد نظر یافت نشد',
        ];
    }
}
