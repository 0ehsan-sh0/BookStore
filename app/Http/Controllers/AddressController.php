<?php

namespace App\Http\Controllers;

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
        return $this->successResponse('عملیات با موفقیت انجام شد', Address::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Address::onlyTrashed()->get());
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|unique:addresses|numeric',
            'city' => 'required',
            'state' => 'required',
            'place_number' => 'required|unique:addresses|numeric',
            'post_code' => 'required|unique:addresses|numeric',
            'address' => 'required',
            'user_id' => 'required|exists:users,id'
        ], [
            'name.required' => 'نام الزامی است',
            'lastname.required' => 'نام خانوادگی الزامی است',
            'state.required' => 'لطفا استان را وارد کنید',
            'city.required' => 'لطفا شهر را وارد کنید',
            'phone.required' => 'شماره همراه الزامی است',
            'phone.unique' => 'شماره همراه تکراری است',
            'phone.numeric' => 'شماره تلفن باید عددی باشد',
            'place_number.required' => 'شماره تلفن خانه الزامی است',
            'place_number.unique' => 'شماره تلفن خانه تکراری است',
            'place_number.numeric' => 'شماره تلفن خانه باید عددی باشد',
            'post_code.required' => 'کد پستی الزامی است',
            'post_code.unique' => 'کد پستی تکراری است',
            'post_code.numeric' => 'کد پستی باید عددی باشد',
            'address.required' => 'آدرس الزامی است',
            'user_id.required' => 'شناسه کاربر الزامی است',
            'user_id.exists' => 'کاربر مورد نظر وجود ندارد'

        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            Address::create($request->all());
            return $this->successResponse('آدرس با موفقیت افزوده شد', '1');
        }
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
    public function update(Request $request, Address $address)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'phone' => 'required|numeric|unique:addresses,phone,' . $address->id,
            'city' => 'required',
            'state' => 'required',
            'place_number' => 'required|numeric|unique:addresses,place_number,'. $address->id,
            'post_code' => 'required|numeric|unique:addresses,post_code,'. $address->id,
            'address' => 'required'
        ], [
            'name.required' => 'نام الزامی است',
            'lastname.required' => 'نام خانوادگی الزامی است',
            'state.required' => 'لطفا استان را وارد کنید',
            'city.required' => 'لطفا شهر را وارد کنید',
            'phone.required' => 'شماره همراه الزامی است',
            'phone.unique' => 'شماره همراه تکراری است',
            'phone.numeric' => 'شماره تلفن باید عددی باشد',
            'place_number.required' => 'شماره تلفن خانه الزامی است',
            'place_number.unique' => 'شماره تلفن خانه تکراری است',
            'place_number.numeric' => 'شماره تلفن خانه باید عددی باشد',
            'post_code.required' => 'کد پستی الزامی است',
            'post_code.unique' => 'کد پستی تکراری است',
            'post_code.numeric' => 'کد پستی باید عددی باشد',
            'address.required' => 'آدرس الزامی است'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            $address->update($request->all());
            return $this->successResponse('آدرس با موفقیت بروزرسانی شد', '1');
        }

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
