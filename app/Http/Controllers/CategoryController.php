<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Category::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Category::onlyTrashed()->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = new Category($request->all());
        $category->main_category()->associate($request->main_category_id); // Associate main category with category
        $category->save();
        return $this->successResponse('دسته بندی با موفقیت افزوده شد', '1');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->all());
        $category->main_category()->associate($request->main_category_id); // Associate main category with category
        $category->save();
        return $this->successResponse('دسته بندی با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse('دسته بندی با موفقیت حذف شد', '1');
    }

    public function restoreData(Category $category)
    {
        $category->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
