<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;

class CommentController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Comment::find($id);
        if ($object) return $object;
        else false;
    }
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
    public function update(UpdateCommentRequest $request, $comment)
    {
        $comment = $this->find($comment);
        if (!$comment) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if (Auth::id() === $comment->user_id) {
            $comment->update($request->only('comment'));
            return $this->successResponse('کامنت با موفقیت بروزرسانی شد', '1');
        }
        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($comment)
    {
        $comment = $this->find($comment);
        if (!$comment) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if (Auth::id() === $comment->user_id || Auth::user()->role === 'admin') {
            $comment->delete();
            return $this->successResponse('کامنت با موفقیت حذف شد', '1');
        }
        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    public function restoreData($comment)
    {
        $comment = Comment::onlyTrashed()->find($comment);
        if ($comment) {
            $comment->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
