<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthApiController extends Controller
{
    public function login_admin()
    {
        if(!empty(Auth::check()) && Auth::user()->is_admin == 1)
        {
            return response()->json(['redirect_url' => 'admin/dashboard']);
        }

        return response()->json(['view' => 'admin.auth.login']);
    }

    public function auth_login_admin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password, 'is_admin' => 1, 'status' => 0, 'is_delete' => 0], $remember))
        {
            return response()->json(['redirect_url' => 'admin/dashboard']);
        }
        else
        {
            return response()->json(['error' => "Please enter correct email and password"], 422);
        }
    }

    public function logout_admin()
    {
        Auth::logout();
        return response()->json(['redirect_url' => 'admin']);
    }
}
