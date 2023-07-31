<?php

namespace App\Http\Controllers;

use App\Models\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TranslatorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Translator::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Translator::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:translators',
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'نام مترجم الزامی است',
            'name.unique' => 'نام مترجم نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            if ($request->hasFile('photo')) {
                $photo_path = $request->file('photo')->store('translators', 'public');
                $translator = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'photo' => $photo_path
                ];
            }
            else {
                $translator = [
                    'name' => $request->name,
                    'description' => $request->description
                ];
            }
            Translator::create($translator);
            return $this->successResponse('مترجم با موفقیت افزوده شد', '1');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Translator $translator)
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', $translator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Translator $translator)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:translators,name,' . $translator->id,
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'نام مترجم الزامی است',
            'name.unique' => 'نام مترجم نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            if ($request->hasFile('photo')) {
                if ($translator->photo) {
                    Storage::disk('public')->delete($translator->photo);
                }
                $photo_path = $request->file('photo')->store('translators', 'public');
                $translator_update = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'photo' => $photo_path
                ];
            }
            else {
                $translator_update = [
                    'name' => $request->name,
                    'description' => $request->description
                ];
            }
            $translator->update($translator_update);
            return $this->successResponse('اطلاعات مترجم با موفقیت بروزرسانی شد', '1');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Translator $translator)
    {
        $translator->delete();
        return $this->successResponse('مترجم با موفقیت حذف شد', '1');
    }

    public function restoreData(Translator $translator)
    {
        $translator->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
