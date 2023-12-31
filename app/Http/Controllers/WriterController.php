<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWriterRequest;
use App\Http\Requests\UpdateWriterRequest;
use App\Models\Writer;
use Illuminate\Support\Facades\Storage;

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
    public function store(StoreWriterRequest $request)
    {
        if ($request->hasFile('photo')) {
            $photo_path = $request->file('photo')->store('writers', 'public');
            $writer = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path,
            ];
        } else {
            $writer = [
                'name' => $request->name,
                'description' => $request->description,
            ];
        }
        Writer::create($writer);

        return $this->successResponse('نویسنده با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show(Writer $writer)
    {
        $writer->load('books');

        // dd(
        //  $writer, $writer->books()->get()
        //  );
        return $this->successResponse('عملیات با موفقیت انجام شد', $writer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWriterRequest $request, Writer $writer)
    {
        if ($request->hasFile('photo')) {
            if ($writer->photo) {
                Storage::disk('public')->delete($writer->photo);
            }
            $photo_path = $request->file('photo')->store('writers', 'public');
            $writer_update = [
                'name' => $request->name,
                'description' => $request->description,
                'photo' => $photo_path,
            ];
        } else {
            $writer_update = [
                'name' => $request->name,
                'description' => $request->description,
            ];
        }
        $writer->update($writer_update);

        return $this->successResponse('اطلاعات نویسنده با موفقیت بروزرسانی شد', '1');
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
