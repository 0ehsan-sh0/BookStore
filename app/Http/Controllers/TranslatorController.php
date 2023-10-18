<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTranslatorRequest;
use App\Http\Requests\UpdateTranslatorRequest;
use App\Models\Translator;
use Illuminate\Support\Facades\Storage;

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
    public function store(StoreTranslatorRequest $request)
    {
        if ($request->hasFile('photo')) {
            $photo_path = $request->file('photo')->store('translators', 'public');
            $translator = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path,
            ];
        } else {
            $translator = [
                'name' => $request->name,
                'description' => $request->description,
            ];
        }
        Translator::create($translator);

        return $this->successResponse('مترجم با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show(Translator $translator)
    {
        $translator->load([
            'books:id,name,english_name,photo,price',
        ]);

        return $this->successResponse('عملیات با موفقیت انجام شد', $translator);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTranslatorRequest $request, Translator $translator)
    {
        if ($request->hasFile('photo')) {
            if ($translator->photo) {
                Storage::disk('public')->delete($translator->photo);
            }
            $photo_path = $request->file('photo')->store('translators', 'public');
            $translator_update = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path,
            ];
        } else {
            $translator_update = [
                'name' => $request->name,
                'description' => $request->description,
            ];
        }
        $translator->update($translator_update);

        return $this->successResponse('اطلاعات مترجم با موفقیت بروزرسانی شد', '1');
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
