<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMainCategoryRequest;
use App\Http\Requests\UpdateMainCategoryRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', MainCategory::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', MainCategory::onlyTrashed()->get());
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
    public function update(UpdateMainCategoryRequest $request, MainCategory $mainCategory)
    {
        $mainCategory->update($request->all());
        return $this->successResponse('دسته بندی اصلی با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MainCategory $mainCategory)
    {
        $mainCategory->delete();
        return $this->successResponse('دسته بندی اصلی با موفقیت حذف شد', '1');
    }

    public function restoreData(MainCategory $mainCategory)
    {
        $mainCategory->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
