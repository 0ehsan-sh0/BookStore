<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Address::all()->latest()->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Address::onlyTrashed()->latest()->paginate(20));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressRequest $request)
    {
            Address::create($request->all());
            return $this->successResponse('آدرس با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show(Address $address)
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', $address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, Address $address)
    {
            $address->update($request->all());
            return $this->successResponse('آدرس با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Address $address)
    {
        $address->delete();
        return $this->successResponse('آدرس با موفقیت حذف شد', '1');
    }

    public function restoreData(Address $address)
    {
        $address->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
