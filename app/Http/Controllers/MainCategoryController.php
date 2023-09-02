<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\MainCategory;
use App\Http\Requests\StoreMainCategoryRequest;
use App\Http\Requests\UpdateMainCategoryRequest;

class MainCategoryController extends ApiController
{
    public function find($id)
    {
        $object = MainCategory::find($id);
        if ($object) return $object;
        else false;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', MainCategory::with('categories')->get());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', MainCategory::with('categories')->onlyTrashed()->get());
    }

    /**
     * Show books of a specific main category
     */
    public function mainCategoryBooks($main_category)
    {
        $main_category = MainCategory::where('url', $main_category)->first();
        if ($main_category) {   //check if the main category exists
            $books = Book::with(['categories', 'translators:id,name', 'writer:id,name'])
                ->whereHas('categories', function ($query) use ($main_category) { // separate each book with the specified category
                    $query->where('main_category_id', $main_category->id);
                })
                ->latest()
                ->paginate(20);
            return $this->successResponse('عملیات با موفقیت انجام شد', $books);
        } else return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', 'مسیر مورد نظر معتبر نیست');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMainCategoryRequest $request)
    {
        MainCategory::create($request->all());
        return $this->successResponse('دسته بندی اصلی با موفقیت افزوده شد', '1');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMainCategoryRequest $request, $mainCategory)
    {
        $mainCategory = $this->find($mainCategory);
        $mainCategory->update($request->all());
        return $this->successResponse('دسته بندی اصلی با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($mainCategory)
    {
        $mainCategory = $this->find($mainCategory);
        if (!$mainCategory) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $mainCategory->delete();
        return $this->successResponse('دسته بندی اصلی با موفقیت حذف شد', '1');
    }

    public function restoreData($mainCategory)
    {
        $mainCategory = MainCategory::onlyTrashed()->find($mainCategory);
        if ($mainCategory) {
            $mainCategory->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
