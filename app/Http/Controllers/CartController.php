<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use Cart;

class CartController extends Controller
{
    /**
     * Cart list Page
     *
     * @return View
     */
    public function index()
    {
        $carts = Cart::content();
        return view('cart', compact('carts'));
    }

    /**
     * Add to cart
     *
     * @return \Illuminate\Http\Response
     */
    public function addCart(Request $request)
    {
        $stock = Stock::findOrFail($request->stock_id);
        Cart::add([
            'id' => $stock->id,
            'name' => $stock->name,
            'qty' => $request->qty ?? 1,
            'price' => $stock->price,
            'weight' => $request->weight ?? 1,
            'options' => ['name_en'=> $stock->name_en, 'imgpath' => $stock->imgpath]
        ]);

        $carts = Cart::content();
        return view('cart', compact('carts'));
    }
}
