<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWriterRequest;
use App\Http\Requests\UpdateWriterRequest;
use App\Models\Writer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class WriterController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Writer::find($id);
        if ($object) return $object;
        else false;
    }
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
    public function store(StoreWriterRequest $request)
    {
        if ($request->hasFile('photo')) {
            $photo_path = $request->file('photo')->store('writers', 'public');
            $writer = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path
            ];
        } else {
            $writer = [
                'name' => $request->name,
                'description' => $request->description
            ];
        }
        Writer::create($writer);
        return $this->successResponse('نویسنده با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show($writer)
    {
        $writer = $this->find($writer);
        if (!$writer) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        return $this->successResponse('عملیات با موفقیت انجام شد', $writer);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWriterRequest $request, $writer)
    {
        $writer = $this->find($writer);
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
        } else {
            $writer_update = [
                'name' => $request->name,
                'description' => $request->description
            ];
        }
        $writer->update($writer_update);
        return $this->successResponse('اطلاعات نویسنده با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($writer)
    {
        $writer = $this->find($writer);
        if (!$writer) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $writer->delete();
        return $this->successResponse('نویسنده با موفقیت حذف شد', '1');
    }

    public function restoreData($writer)
    {
        $writer = Writer::onlyTrashed()->find($writer);
        if ($writer) {
            $writer->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
