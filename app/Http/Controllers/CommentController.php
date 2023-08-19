<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Comment::all()->paginate(20));
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
        Comment::create($request->all());
        return $this->successResponse('کامنت با موفقیت افزوده شد', '1');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->all());
        return $this->successResponse('کامنت با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::user()->id === $comment->user_id || Auth::user()->role === 'admin') {
            $comment->delete();
            return $this->successResponse('کامنت با موفقیت حذف شد', '1');
        }
        return $this->errorResponse('شما نمیتوانید نظرات دیگران را حذف کنید', '', 401);
    }

    public function restoreData(Comment $comment)
    {
        $comment->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
