<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Response;

class UserController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = User::find($id);
        if ($object) return $object;
        else false;
    }
    // ---------------------------------------------------------------- Register , Login And Logout
    public function register(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            return $this->successResponse(
                'ثبت نام با موفقیت انجام شد',
                [
                    'token' => $user->createToken('Api token id : ' . $user->id)
                        ->plainTextToken,
                    'user' => $user
                ]
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('خطا از سمت سرور', '', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password')))
                return $this->errorResponse('ایمیل یا رمز عبور اشتباه است', '', 401);
            // Authentication passed...
            $user = User::with('comments', 'carts', 'addresses')->where('email', $request->email)->first();
            return $this->successResponse(
                'ورود با موفقیت انجام شد',
                [
                    'token' => $user->createToken('Api token id :' . $user->id)
                        ->plainTextToken,
                    'user' => $user
                ]
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('خطا از سمت سرور', '', 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->successResponse('شما از حساب کاربری خود خارج شدید', '', Response::HTTP_FOUND);
    }
    // Register , Login And Logout ---------------------------------------------------------------- 

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', User::latest()->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', User::onlyTrashed()->latest()->paginate(20));
    }

    /**
     * Display the specified resource.
     */
    public function show($user)
    {
        $user = User::with('comments', 'carts', 'addresses')
            ->find($user);
        if (!$user) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        return $this->successResponse('عملیات با موفقیت انجام شد', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $user)
    {
        $user = $this->find($user);
        if ($user->id === Auth::id() || Auth::user()->role === 'admin') {
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            if ($request->has('password')) {
                if ($request->has('current_password')) {
                    if (Hash::check($request->current_password, Auth::user()->password)) {
                        $user->password = Hash::make($request->password);
                    } else return $this->errorResponse('خطای رمز عبور', 'رمز عبور کنونی نادرست است', 401);
                } else if (Auth::user()->role === 'admin') $user->password = Hash::make($request->password);
            }
            $user->melicode = $request->melicode;
            $user->birthdate = $request->birthdate;
            $user->gender = $request->gender;
            $user->state = $request->state;
            $user->city = $request->city;
            if (Auth::user()->role === 'admin')
                $user->role = $request->role;

            $user->save();

            return $this->successResponse('اطلاعات با موفقیت بروزرسانی شد', $user);
        }
        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user)
    {
        $user = $this->find($user);
        if (!$user) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if ($user->id === Auth::id() || Auth::user()->role === 'admin') {
            $user->delete();
            return $this->successResponse('کاربر با موفقیت حذف شد', '1');
        }
        return $this->errorResponse('خطای سطح دسترسی', '', 401);
    }

    public function restoreData($user)
    {
        $user = User::onlyTrashed()->find($user);
        if ($user) {
            $user->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
