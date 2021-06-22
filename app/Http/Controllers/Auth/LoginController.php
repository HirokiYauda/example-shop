<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        logout as performLogout;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * ログイン直後の処理を追加
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * 
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // ログイン前に、カート情報を持っていた場合、DBに保存しておく
        $carts_count = Cart::content()->count();
        if (!empty($carts_count)) {
            // もしカート情報を持っていたら、削除して再登録
            Cart::erase($user->id);
            Cart::store($user->id);
        }

        // ログイン後のリダイレクト
        return redirect()->intended($this->redirectPath());
    }

    /**
     * ログアウト直前の処理を追加
     * 
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        // ログアウト前に、カート情報を持っていた場合、DBに保存しておく
        $user = Auth::user();
        $carts_count = Cart::content()->count();
        if (!empty($user) && !empty($carts_count)) {
            // もしカート情報を持っていたら、削除して再登録
            Cart::erase($user->id);
            Cart::store($user->id);
        }

        $this->performLogout($request);

        return redirect()->route('top');
    }
}
