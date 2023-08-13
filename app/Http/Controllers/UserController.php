<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    // ---------------------------------------------------------------- Register , Login And Logout
    public function register(StoreUserRequest $request)
    {
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
    public function update(UpdateUserRequest $request, User $user)
    {
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
