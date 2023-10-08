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
            $fields = [
                'name' => $request->name,
                'lastname' => $request->lastname,
                'password' => Hash::make($request->password),
            ];
            if ($request->email) $fields['email'] = $request->email;
            else if ($request->phone) $fields['phone'] = $request->phone;
            else return $this->errorResponse('لطفا شماره موبایل یا ایمیل را وارد کنید', null);
            $user = User::create($fields);
            return $this->successResponse(
                'ثبت نام با موفقیت انجام شد',
                [
                    'token' => $user->createToken('Api token id : ' . $user->id)
                        ->plainTextToken,
                    'user' => $user
                ]
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('خطا از سمت سرور', null, 500);
        }
    }

    public function login(Request $request)
    {
        try {
            if ($request->phone) {
                $user = User::with('carts', 'addresses')->where('phone', $request->phone)->first();
                if (!$user) return $this->errorResponse('تلفن یا رمز عبور اشتباه است', '', 401);
                if (Hash::check($request->password, $user->password)) {
                    return $this->successResponse(
                        'ورود با موفقیت انجام شد',
                        [
                            'token' => $user->createToken('Api token id :' . $user->id)
                                ->plainTextToken,
                            'user' => $user
                        ]
                    );
                } else return $this->errorResponse('تلفن یا رمز عبور اشتباه است', '', 401);
            }
            if (!Auth::attempt($request->only('email', 'password')))
                return $this->errorResponse('ایمیل یا رمز عبور اشتباه است', '', 401);
            // Authentication passed...
            $user = User::with('carts', 'addresses')->where('email', $request->email)->first();
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
     * ----------------------------- Promoting and demoting users.
     */
    public function promote($user)
    {
        $user = $this->find($user);
        if (!$user) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if ($user->role === 'admin') return $this->errorResponse(
            'لطفا خطاهای زیر را بررسی کنید',
            'سطح دسترسی کاربر ادمین است و نیازی به ارتقا ندارد'
        );
        $user->update([
            'role' => 'admin'
        ]);
        return $this->successResponse('عملیات با موفقیت انجام شد', 'سطح دسترسی کاربر به ادمین تغییر پیدا کرد');
    }

    public function demote($user)
    {
        $user = $this->find($user);
        if (!$user) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if ($user->role === 'user') return $this->errorResponse(
            'لطفا خطاهای زیر را بررسی کنید',
            'سطح دسترسی کاربر کاربر معمولی است و نیازی به تغییر ندارد'
        );
        $user->update([
            'role' => 'user'
        ]);
        return $this->successResponse('عملیات با موفقیت انجام شد', 'سطح دسترسی کاربر به کاربر معمولی تغییر پیدا کرد');
    }
    /**
     * Promoting and demoting users. -----------------------------
     */

    /**
     * Get the Auth user information including carts and addresses.
     */
    public function getInfo()
    {
        $user = User::with(['addresses', 'carts'])->find(Auth::id());
        return $this->successResponse('عملیات با موفقیت انجام شد', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $this->find(Auth::id());
        if ($user->id === Auth::id()) {
            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;
            if ($request->password && $request->current_password) {
                if (Hash::check($request->current_password, Auth::user()->password))
                    $user->password = Hash::make($request->password);
                else return $this->errorResponse('خطای رمز عبور', 'رمز عبور کنونی نادرست است', 401);
            }
            $user->phone = $request->phone;
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
