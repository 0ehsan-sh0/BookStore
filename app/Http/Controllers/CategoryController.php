<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Book;
use App\Models\Category;
use App\Models\MainCategory;

class CategoryController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Category::find($id);
        if ($object) return $object;
        else false;
    }
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

    // Get all books of a category
    public function categoryBooks($main_category, $category)
    {
        $mainCategory = MainCategory::where('url', $main_category)->first();
        if ($mainCategory) { //check if the main category exists
            foreach ($mainCategory->categories as $index => $Mcategory) {
                if ($Mcategory->url === $category) { // check if the category exists
                    $books = Book::with(['categories', 'translators:id,name', 'writer:id,name'])
                        ->whereHas('categories', function ($query) use ($category) { // separate each book with the specified category
                            $query->where('url', $category);
                        })
                        ->latest()
                        ->paginate(20);
                    return $this->successResponse('عملیات با موفقیت انجام شد', $books);
                }
            }
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', 'مسیر مورد نظر معتبر نیست');
        } else return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', 'مسیر مورد نظر معتبر نیست');
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
    public function update(UpdateCategoryRequest $request, $category)
    {
        $category = $this->find($category);
        $category->update($request->all());
        $category->main_category()->associate($request->main_category_id); // Associate main category with category
        $category->save();
        return $this->successResponse('دسته بندی با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($category)
    {
        $category = $this->find($category);
        if (!$category) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $category->delete();
        return $this->successResponse('دسته بندی با موفقیت حذف شد', '1');
    }

    public function restoreData($category)
    {
        $category = Category::onlyTrashed()->find($category);
        if ($category) {
            $category->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
