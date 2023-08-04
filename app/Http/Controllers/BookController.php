<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BookController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Book::with(['categories', 'translators:id,name'])->latest()->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Book::onlyTrashed()->with(['categories', 'translators:id,name'])->latest()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        ], [
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
        ]);
        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            $photo_path = $request->file('photo')->store('books', 'public');
            $code = random_int(10000000, 99999999);
            while (Book::where('code', $code)->count() > 0) {
                $code = random_int(10000000, 99999999);
            }
            $book = [
                'code' => $code,
                'name' => $request->name,
                'english_name' => $request->english_name,
                'price' => $request->price,
                'photo' => $photo_path,
                'print_series' => $request->print_series,
                'isbn' => $request->isbn,
                'book_cover_type' => $request->book_cover_type,
                'format' => $request->format,
                'pages' => $request->pages,
                'publish_year' => $request->publish_year,
                'count' => $request->count,
                'description' => $request->description,
                'writer_id' => $request->writer_id,
            ];

            $book_created = Book::create($book);
            $book_created->categories()->attach($request->input('categories', []));
            $book_created->translators()->attach($request->input('translators', []));
            return $this->successResponse('کتاب با موفقیت افزوده شد', '1');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Book::with(['categories', 'translators:id,name'])
            ->where('id', '=', $book->id)
            ->first());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'english_name' => 'nullable|regex:/^[a-zA-Z0-9 ]+$/',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'photo' => 'nullable|mimes:jpg,jpeg,png|max:3072',
            'print_series' => 'required|numeric|min:0|max:65533',
            'isbn' => 'required|unique:books,isbn' . $book->id,
            'format' => 'required',
            'pages' => 'required|numeric|min:1',
            'publish_year' => 'required|numeric|min:1000',
            'count.required' => 'required|numeric|min:0',
            'writer_id' => 'required|exists:writers,id',
            'categories' => 'required',
            'categories.*' => 'exists:categories,id',
            'translators' => 'required',
            'translators.*' => 'exists:translators,id'
        ], [
            'name.required' => 'نام کتاب الزامی است',
            'english_name.regex' => 'لطفا نام انگلیسی را با حروف انگلیسی وارد کنید',
            'description.required' => 'توضیحات الزامی است',
            'price.required' => 'فیلد قیمت الزامی است',
            'price.numeric' => 'فیلد قیمت عددی است',
            'price.min' => 'نمیتوانید در این فیلد مقدار منفی وارد کنید',
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
            'categories.required' => 'این فیلد الزامی است',
            'categories.*.exists' => 'دسته بندی مورد نظر یافت نشد',
            'translators.required' => 'این فیلد الزامی است',
            'translators.*.exists' => 'مترجم مورد نظر یافت نشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            if ($request->hasFile('photo')) {
                if ($book->photo) {
                    Storage::disk('public')->delete($book->photo);
                }
                $photo_path = $request->file('photo')->store('books', 'public');
                $book_update = [
                    'name' => $request->name,
                    'english_name' => $request->english_name,
                    'price' => $request->price,
                    'photo' => $photo_path,
                    'print_series' => $request->print_series,
                    'isbn' => $request->isbn,
                    'book_cover_type' => $request->book_cover_type,
                    'format' => $request->format,
                    'pages' => $request->pages,
                    'publish_year' => $request->publish_year,
                    'count' => $request->count,
                    'description' => $request->description,
                    'writer_id' => $request->writer_id,
                ];
            } else {
                $book_update = [
                    'name' => $request->name,
                    'english_name' => $request->english_name,
                    'price' => $request->price,
                    'print_series' => $request->print_series,
                    'isbn' => $request->isbn,
                    'book_cover_type' => $request->book_cover_type,
                    'format' => $request->format,
                    'pages' => $request->pages,
                    'publish_year' => $request->publish_year,
                    'count' => $request->count,
                    'description' => $request->description,
                    'writer_id' => $request->writer_id,
                ];
            }
            $book->update($book_update);
            $book->categories()->sync($request->input('categories', []));
            $book->translators()->sync($request->input('translators', []));
            return $this->successResponse('اطلاعات کتاب با موفقیت بروزرسانی شد', '1');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return $this->successResponse('کتاب با موفقیت حذف شد', '1');
    }

    public function restoreData(Book $book)
    {
        $book->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
