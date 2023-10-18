<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Book::with(['categories', 'translators:id,name', 'writer:id,name'])->latest()->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Book::onlyTrashed()->with(['categories', 'translators:id,name', 'writer:id,name'])->latest()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $photo_path = $request->file('photo')->store('books', 'public');
        do {
            $code = random_int(100000000, 999999999);
        } while (Book::where('code', $code)->count() > 0);
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
            'publisher' => $request->publisher,
            'count' => $request->count,
            'description' => $request->description,
            'writer_id' => $request->writer_id,
        ];
        $book_created = Book::create($book);
        $book_created->categories()->attach($request->input('categories', []));
        $book_created->translators()->attach($request->input('translators', []));
        $book_created->tags()->attach($request->input('tags', []));

        return $this->successResponse('کتاب با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load([
            'categories' => function ($query) {
                $query->with('main_category');
            },
            'translators:id,name',
            'writer:id,name',
            'comments' => function ($query) {
                $query->with('user:id,name,lastname,role')->where('status', true);
            },
            'tags:name,url',
        ]);

        return $this->successResponse('عملیات با موفقیت انجام شد', $book);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
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
                'publisher' => $request->publisher,
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
                'publisher' => $request->publisher,
                'count' => $request->count,
                'description' => $request->description,
                'writer_id' => $request->writer_id,
            ];
        }
        $book->update($book_update);
        $book->categories()->sync($request->input('categories', []));
        $book->translators()->sync($request->input('translators', []));
        $book->tags()->sync($request->input('tags', []));

        return $this->successResponse('اطلاعات کتاب با موفقیت بروزرسانی شد', '1');
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
