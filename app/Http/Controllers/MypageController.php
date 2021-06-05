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
     * User profile edit page
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
     * User profile edit page
     *
     * @return View
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id.',id'],
            'zip' => ['required', new ZipcodeRule],
            'pref' => ['required', 'integer'],
            'address1' => ['required', 'string', 'max:200'],
            'address2' => ['required', 'string', 'max:200'],
        ]);
        // ハイフンなし
        $validatedData['zip'] = str_replace("-", "", $validatedData['zip']);
        
        $res = DB::transaction(function () use ($validatedData, $user) {
            $result = $user->fill($validatedData)->save();
            return $result;
        });

        // 更新の結果によってメッセージを変更
        $message = "アカウントの更新に失敗しました。お手数ですが、もう一度やり直してください。";
        if (!empty($res)) {
            $message = "アカウントを更新しました。";
        }

        return redirect()->route('mypage_edit')->with('update_message', $message);
    }
}
