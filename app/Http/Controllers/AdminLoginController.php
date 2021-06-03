<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    public function checkLogin(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password, 'active'=> 1, 'role'=> 1])) {
            
            return redirect()->route('license.all');
            
        } else {
            Session::flash('danger', 'Sai thông tin đăng nhập');
            return redirect()->back();
        }
    }

    public function login()
    {
        return view('login');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login');
    }
}
