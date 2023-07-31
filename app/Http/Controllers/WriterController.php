<?php

namespace App\Http\Controllers;

use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WriterController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Writer::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Writer::onlyTrashed()->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:writers',
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'نام نویسنده الزامی است',
            'name.unique' => 'نام نویسنده نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            if ($request->hasFile('photo')) {
                $photo_path = $request->file('photo')->store('writers', 'public');
                $writer = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'photo' => $photo_path
                ];
            }
            else {
                $writer = [
                    'name' => $request->name,
                    'description' => $request->description
                ];
            }
            Writer::create($writer);
            return $this->successResponse('نویسنده با موفقیت افزوده شد', '1');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Writer $writer)
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', $writer);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Writer $writer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:writers,name,' . $writer->id,
            'photo' => 'mimes:jpg,jpeg,png|max:2048'
        ], [
            'name.required' => 'نام نویسنده الزامی است',
            'name.unique' => 'نام نویسنده نمیتواند تکراری باشد',
            'photo.mimes' => 'فرمت فایل باید از نوع png,jpeg,jpg باشد',
            'photo.max' => 'حجم فایل نباید بیشتر از دو مگابایت باشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            if ($request->hasFile('photo')) {
                if ($writer->photo) {
                    Storage::disk('public')->delete($writer->photo);
                }
                $photo_path = $request->file('photo')->store('writers', 'public');
                $writer_update = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'photo' => $photo_path
                ];
            }
            else {
                $writer_update = [
                    'name' => $request->name,
                    'description' => $request->description
                ];
            }
            $writer->update($writer_update);
            return $this->successResponse('اطلاعات نویسنده با موفقیت بروزرسانی شد', '1');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Writer $writer)
    {
        $writer->delete();
        return $this->successResponse('نویسنده با موفقیت حذف شد', '1');
    }

    public function restoreData(Writer $writer)
    {
        $writer->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
