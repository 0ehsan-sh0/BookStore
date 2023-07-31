<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    // ---------------------------------------------------------------- Register , Login And Logout
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ], [
            'name.required' => 'فیلد نام الزامی است',
            'lastname.required' => 'فیلد نام خانوادگی الزامی است',
            'email.required' => 'فیلد ایمیل الزامی است',
            'email.unique' => 'ایمیل مورد نظر قبلا ثبت شده است',
            'email.email' => 'لطفا ایمیل را صحیح وارد کنید',
            'password.required' => 'فیلد رمز عبور الزامی است',
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        }

        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json(['success' => true]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication passed...
            $user = User::where('email', $request->email)->first();
            return $this->successResponse('ورود با موفقیت انجام شد', $user);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json(['success' => true]);
    }
    // Register , Login And Logout ---------------------------------------------------------------- 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', User::all());
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', User::onlyTrashed()->get());
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'melicode' => 'required|iran_national_id',
            'birthdate' => 'required|date',
            'gender' => 'required|in:male,female',
            'state' => 'required',
            'city' => 'required',
            'role' => 'required|in:user,admin',
        ], [
            'name.required' => 'فیلد نام الزامی است',
            'lastname.required' => 'فیلد نام خانوادگی الزامی است',
            'email.required' => 'فیلد ایمیل الزامی است',
            'email.unique' => 'ایمیل مورد نظر قبلا ثبت شده است',
            'email.email' => 'لطفا ایمیل را صحیح وارد کنید',
            'password.confirmed' => 'رمز عبور با تکرار آن مطابقت ندارد',
            'password.min' => 'رمز عبور باید حداقل 8 کاراکتر باشد',
            'melicode.required' => 'کد ملی الزامی است',
            'melicode.iran_national_id' => 'کد ملی را صحیح وارد کنید',
            'birthdate.required' => 'تاریخ تولد الزامی است',
            'birthdate.date' => 'لطفا تاریخ تولد را صحیح وارد کنید',
            'gender.required' => 'لطفا جنسیت را وارد کنید',
            'gender.in' => 'لطفا جنسیت را صحیح وارد کنید',
            'state.required' => 'لطفا استان را وارد کنید',
            'city.required' => 'لطفا شهر را وارد کنید',
            'role.required' => 'لطفا سطح دسترسی را وارد کنید',
            'role.in' => 'لطفا سطح دسترسی را درست وارد کنید'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', $validator->errors());
        } else {
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->melicode = $request->melicode;
            $user->birthdate = $request->birthdate;
            $user->gender = $request->gender;
            $user->state = $request->state;
            $user->city = $request->city;
            $user->role = $request->role;

            $user->save();

            return $this->successResponse('اطلاعات کاربر با موفقیت بروزرسانی شد', $user);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->successResponse('کاربر با موفقیت حذف شد', '1');
    }

    public function restoreData(User $user)
    {
        $user->restore();
        return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
    }
}
