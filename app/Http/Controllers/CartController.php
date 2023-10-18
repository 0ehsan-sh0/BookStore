<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartRequest;
use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CartController extends ApiController
{
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
        if (! $request->ischeckedout) {
            return $this->errorResponse('لطفا قبل از ثبت سفارش پرداخت نهایی را انجام دهید', null);
        }
        do {
            $code = random_int(100000000, 999999999);
        } while (Cart::where('code', $code)->count() > 0);
        $cart = [
            'code' => $code,
            'ischeckedout_at' => Carbon::now(),
            'total_price' => 0,
            'address_id' => $request->address_id,
            'user_id' => Auth::id(),
        ];
        $cart_created = Cart::create($cart);

        // Attach each book with its count individually
        $books = $request->input('books', []);
        $counts = $request->input('counts', []);
        foreach ($books as $index => $book) {
            $cart_created->books()->attach($book, ['count' => $counts[$index]]);
        }
        $cart_created->total_price = $this->countTotalPrice($cart_created);
        $cart_created->save();

        return $this->successResponse('سبد خرید با موفقیت افزوده شد', '1');
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        $cart = Cart::with(['user:id,name,lastname,email', 'books:id,name,photo,price,isbn'])
            ->find($cart->id);
        if (Auth::id() !== $cart->user_id) {
            return $this->errorResponse('خطای سطح دسترسی', '', 401);
        }

        return $this->successResponse('عملیات با موفقیت انجام شد', $cart);
    }
}
