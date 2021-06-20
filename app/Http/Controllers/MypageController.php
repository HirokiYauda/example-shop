<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pref;
use App\Rules\ZipcodeRule;
use Auth;
use DB;

class MypageController extends Controller
{
    /**
     * ユーザーアカウント管理
     *
     * @return View
     */
    public function edit()
    {
        $prefs = Pref::all();
        $user = Auth::user();
        return view('mypage.edit', compact('prefs', 'user'));
    }

    /**
     * 住所変更
     *
     * @return View
     */
    public function changeAddress()
    {
        $prefs = Pref::all();
        $user = Auth::user();
        
        return view('mypage.change_address', compact('prefs', 'user'));
    }

    /**
     * 指定ユーザー情報を全て更新処理
     *
     * @return Redirect
     */
    public function fullUpdate(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id.',id'],
            'zip' => ['required', new ZipcodeRule],
            'pref_id' => ['required', 'integer'],
            'address1' => ['required', 'string', 'max:200'],
            'address2' => ['required', 'string', 'max:200'],
        ]);

        $title = "アカウント";
        // ユーザーテーブル更新処理
        $message = $this->update($user, $validatedData, $title);
        return redirect()->route('mypage_edit')->with('update_message', $message);
    }

    /**
     * 指定ユーザー情報の住所更新処理
     *
     * @return Redirect
     */
    public function addressUpdate(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'zip' => ['required', new ZipcodeRule],
            'pref_id' => ['required', 'integer'],
            'address1' => ['required', 'string', 'max:200'],
            'address2' => ['required', 'string', 'max:200'],
        ]);

        $title = "住所";
        // ユーザーテーブル更新処理
        $message = $this->update($user, $validatedData, $title);
        return redirect()->route('order')->with('update_message', $message);
    }

    /**
     * ユーザーテーブル更新処理
     *
     * @return String
     */
    public function update($user, $validatedData, $title)
    {
        // ハイフントル
        $validatedData['zip'] = str_replace("-", "", $validatedData['zip']);
        
        DB::transaction(function () use ($validatedData, $user) {
            $user->fill($validatedData)->save();
        });

        return "${title}を更新しました。";
    }
}
