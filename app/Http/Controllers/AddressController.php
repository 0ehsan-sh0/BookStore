<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;

class AddressController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Address::find($id);
        if ($object) return $object;
        else false;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Address::latest()->paginate(20));
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
        $requestData = $request->all();
        $requestData['user_id'] = Auth::id(); // Add the authenticated user's ID to the request data
        Address::create($requestData);
        return $this->successResponse('آدرس با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show($address)
    {
        $address = $this->find($address);
        if (!$address) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if (Auth::id() !== $address->user_id)
            return $this->errorResponse('خطای سطح دسترسی', '', 401);
        return $this->successResponse('عملیات با موفقیت انجام شد', $address);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressRequest $request, $address)
    {
        $address = $this->find($address);
        if (Auth::id() !== $address->user_id)
            return $this->errorResponse('خطای سطح دسترسی', '', 401);
        $address->update($request->all());
        return $this->successResponse('آدرس با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($address)
    {
        $address = $this->find($address);
        if (!$address) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if (Auth::id() !== $address->user_id)
            return $this->errorResponse('خطای سطح دسترسی', '', 401);
        $address->delete();
        return $this->successResponse('آدرس با موفقیت حذف شد', '1');
    }

    public function restoreData($address)
    {
        $address = Address::onlyTrashed()->find($address);
        if ($address) {
            $address->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
