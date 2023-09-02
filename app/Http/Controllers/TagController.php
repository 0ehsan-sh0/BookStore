<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Article;
use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends ApiController
{
    public function find($id)
    {
        $object = Tag::find($id);
        if ($object) return $object;
        else false;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Tag::latest()->paginate(30));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Tag::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        Tag::create($request->all());
        return $this->successResponse('تگ با موفقیت افزوده شد', '1');
    }

    /**
     * Display books and Articles of a tag.
     */
    public function tagBooksAndArticles($tag)
    {
        $tag = Tag::where('url', $tag)->first();
        if (!$tag) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $books = Book::with(['categories', 'translators:id,name', 'writer:id,name'])
        ->whereHas('tags', function($query) use ($tag) {
            return $query->where('url', $tag->url);
        })->latest()->paginate(20);
        $article = Article::select('id', 'title','subtitle', 'user_id', 'created_at', 'updated_at')
        ->with(['user:id,name,lastname'])
        ->whereHas('tags', function($query) use ($tag) {
            return $query->where('url', $tag->url);
        })->latest()->paginate(10);
        return $this->successResponse('عملیات با موفقیت انجام شد', ['books' => $books,'articles' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, $tag)
    {
        $tag = $this->find($tag);
        $tag->update($request->all());
        return $this->successResponse('تگ با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($tag)
    {
        $tag = $this->find($tag);
        if (!$tag) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $tag->delete();
        return $this->successResponse('تگ با موفقیت حذف شد', '1');
    }

    public function restoreData($tag)
    {
        $tag = Tag::onlyTrashed()->find($tag);
        if ($tag) {
            $tag->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
