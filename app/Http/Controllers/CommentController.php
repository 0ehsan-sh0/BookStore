<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Comment::latest()->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Comment::onlyTrashed()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request)
    {
        $request['user_id'] = Auth::id();
        Comment::create($request->all());

        return $this->successResponse('کامنت با موفقیت افزوده شد', '1');
    }

    /**
     * Update the specified resource in storage.
     */
    public function confirm(Comment $comment)
    {
        $comment->status = true;
        $comment->save();

        return $this->successResponse('کامنت با موفقیت تایید شد', '1');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        if (Auth::id() === $comment->user_id) {
            $comment->update($request->only('comment'));

            return $this->successResponse('کامنت با موفقیت بروزرسانی شد', '1');
        }

        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() === $comment->user_id || Auth::user()->role === 'admin') {
            $comment->delete();

            return $this->successResponse('کامنت با موفقیت حذف شد', '1');
        }

        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    public function restoreData(Comment $comment)
    {
        $comment->restore();

        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
