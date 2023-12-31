<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticleController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Article::with('user:id,name,lastname')
            ->latest()
            ->paginate(10));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Article::onlyTrashed()
            ->with(['user:id,name,lastname'])
            ->latest()
            ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        if ($request->photo) {
            $article['photo'] = $request->file('photo')->store('articles', 'public');
        }
        $article['title'] = $request->title;
        $article['description'] = $request->description;
        $article['subtitle'] = $request->subtitle;
        $article['user_id'] = Auth::id();
        $article = Article::create($article);
        $article->tags()->attach($request->input('tags', []));

        return $this->successResponse('عملیات با موفقیت انجام شد', $article);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $article = Article::with([
            'comments' => function ($query) {
                $query->with('user:id,name,lastname,role')->where('status', true);
            },
            'tags',
            'user:id,name,lastname',
        ])
            ->find($article->id);

        return $this->successResponse('عملیات با موفقیت انجام شد', $article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        if (! ($article->user_id === Auth::id())) {
            return $this->errorResponse('خطای سطح دسترسی', '', 401);
        }
        if ($request->hasFile('photo')) {
            if ($article->photo) {
                Storage::disk('public')->delete($article->photo);
            }
            $article_update['photo'] = $request->file('photo')->store('articles', 'public');
        }
        $article_update['title'] = $request->title;
        $article_update['description'] = $request->description;
        $article_update['subtitle'] = $request->subtitle;
        $article->update($article_update);
        $article->tags()->sync($request->input('tags', []));

        return $this->successResponse('اطلاعات مقاله با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return $this->successResponse('مقاله با موفقیت حذف شد', '1');
    }

    public function restoreData(Article $article)
    {
        $article->restore();

        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
