<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Http\Requests\UpdateCartRequest;
use Carbon\Carbon;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends ApiController
{
    // Find a specific object with id
    public function find($id)
    {
        $object = Cart::find($id);
        if ($object) return $object;
        else false;
    }

    // Function for calculating the total price
    public function countTotalPrice($cart)
    {
        $totalPrice = 0;
        foreach ($cart->books as $book) {
            $totalPrice += $book->price * $book->pivot->count;
        }
        return $totalPrice;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Cart::with(['user:id,name,lastname,email', 'books:id,name,photo,price,isbn'])
            ->latest()
            ->paginate(20));
    }

    public function trashed()
    {
        return $this->successResponse('عملیات با موفقیت انجام شد', Cart::onlyTrashed()
            ->with(['user:id,name,lastname,email', 'books:id,name,photo,isbn'])
            ->latest()
            ->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartRequest $request)
    {
        do $code = random_int(100000000, 999999999);
        while (Cart::where('code', $code)->count() > 0);
        $request->ischeckedout ? $checkedout_time = Carbon::now() : $checkedout_time = null;
        $cart = [
            'code' => $code,
            'ischeckedout' => $request->ischeckedout,
            'checkedout_time' => $checkedout_time,
            'total_price' => 0,
            'user_id' => $request->user_id
        ];
        $cart_created = Cart::create($cart);

        // Attach each book with its count individually
        $books = $request->input('books', []);
        $counts = $request->input('counts', []);
        foreach ($books as $index => $book) {
            $cart_created->books()->attach($book, ['count' => $counts[$index]]);
        }
        $totalPrice = $this->countTotalPrice($cart_created);
        $cart_created->total_price = $totalPrice;
        $cart_created->save();
        return $this->successResponse('سبد خرید با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show($cart)
    {
        $cart = Cart::with(['user:id,name,lastname,email', 'books:id,name,photo,price,isbn'])
            ->find($cart);
        if (!$cart) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        return $this->successResponse('عملیات با موفقیت انجام شد', $cart);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, $cart)
    {
        $cart = $this->find($cart);
        if (!$cart) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        if ($cart->ischeckedout) {
            return $this->errorResponse('لطفا خطاهای زیر را بررسی کنید', 'نمیتوانید اطلاعات سبد خریدی را که پرداخت آن نهایی شده است تغییر دهید');
        }
        $request->ischeckedout ? $checkedout_time = Carbon::now() : $checkedout_time = null;
        $cart_update = [
            'ischeckedout' => $request->ischeckedout,
            'checkedout_time' => $checkedout_time,
            'total_price' => 0,
            'user_id' => $request->user_id
        ];
        $cart->update($cart_update);

        // Sync each book
        $books = $request->input('books', []);
        $counts = $request->input('counts', []);
        $cart->books()->sync($books);

        // Update count for each book in the pivot table
        foreach ($books as $index => $book) {
            $count = $counts[$index];
            $cart->books()->updateExistingPivot($book, ['count' => $count]);
        }
        $totalPrice = $this->countTotalPrice($cart);
        $cart->total_price = $totalPrice;
        $cart->save();
        return $this->successResponse('سبد خرید با موفقیت بروزرسانی شد', '1');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cart)
    {
        $cart = $this->find($cart);
        if (!$cart) return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
        $cart->delete();
        return $this->successResponse('سبد خرید با موفقیت حذف شد', '1');
    }

    public function restoreData($cart)
    {
        $cart = Cart::onlyTrashed()->find($cart);
        if ($cart) {
            $cart->restore();
            return $this->successResponse('اطلاعات با موفقیت بازیابی شد', '1');
        } else return $this->errorResponse('مسیر مورد نظر معتبر نیست', '');
    }
}
