<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role_id !== 1) { // Kiểm tra role_id = 1 cho Admin
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        return view('admin.dashboard');
    }

    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('home');
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Kiểm tra vai trò của người dùng
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');  // Chuyển hướng đến admin.dashboard
            } elseif ($user->role === 'author') {
                return redirect()->route('author.dashboard');  // Chuyển hướng đến author.dashboard
            } else {
                return redirect()->route('home');  // Chuyển hướng đến home cho người dùng là reader
            }
        }
    
        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }

    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
