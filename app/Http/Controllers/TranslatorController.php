<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslatorRequest;
use App\Http\Requests\UpdateTranslatorRequest;
use App\Models\Translator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TranslatorController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Translator::find($id);
        if ($object) return $object;
        else false;
    }
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
    public function store(StoreTranslatorRequest $request)
    {
        if ($request->hasFile('photo')) {
            $photo_path = $request->file('photo')->store('translators', 'public');
            $translator = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path
            ];
        } else {
            $translator = [
                'name' => $request->name,
                'description' => $request->description
            ];
        }
        Translator::create($translator);
        return $this->successResponse('مترجم با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show($translator)
    {
        $translator = $this->find($translator);
        if (!$translator) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        return $this->successResponse('عملیات با موفقیت انجام شد', $translator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranslatorRequest $request, $translator)
    {
        $translator = $this->find($translator);
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
        } else {
            $translator_update = [
                'name' => $request->name,
                'description' => $request->description
            ];
        }
        $translator->update($translator_update);
        return $this->successResponse('اطلاعات مترجم با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($translator)
    {
        $translator = $this->find($translator);
        if (!$translator) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $translator->delete();
        return $this->successResponse('مترجم با موفقیت حذف شد', '1');
    }

    public function restoreData($translator)
    {
        $translator = Translator::onlyTrashed()->find($translator);
        if ($translator) {
            $translator->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
