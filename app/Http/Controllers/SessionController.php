<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SessionController extends Controller
{
    // 中间件限制登录用户
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    // 登录表单
    public function create()
    {
        return view('sessions.create');
    }

    // 登录校验
    public function store(Request $request)
    {
        $credentials = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->has('remember')))
        {
            // 登录成功的相关操作
            session()->flash('success', '欢迎回来!');
            $fallback = route('users.show', Auth::user());
            return redirect()->intended($fallback);
        } else {
            // 登录失败的相关操作
            session()->flash('danger', '很抱歉, 您的邮箱和密码不匹配');
            return redirect()->back()->withInput();
        }
    }

    // 退出登录
    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出!');
        return redirect('/');
    }
}
