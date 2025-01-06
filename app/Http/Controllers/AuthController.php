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
    public function authorDashboard()
    {
        if (Auth::user()->role_id !== 2) {
            abort(403, 'Bạn không có quyền truy cập trang này auther.');
        }

        return view('author.dashboard'); // Trả về view của author
    }

    // Dashboard cho Reader
    public function readerHome()
    {
        if (Auth::user()->role_id !== 3) {
            abort(403, 'Bạn không có quyền truy cập trang này reader.');
        }

        return view('reader.home'); // Trả về view của reader
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
        return redirect()->route('dashboard');
    }

    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    // Xử lý đăng nhập
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Điều hướng theo vai trò
            switch ($user->role_id) {
                case 1: // Admin
                    return redirect()->route('admin.dashboard');
                case 2: // Author
                    return redirect()->route('author.dashboard');
                case 3: // Reader
                    return redirect()->route('reader.home');
                default:
                    Auth::logout();
                    return back()->withErrors(['email' => 'Không xác định được vai trò người dùng.']);
            }
        }

        return back()->withErrors(['email' => 'Email hoặc mật khẩu không đúng']);
    }


    // Đăng xuất
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
