<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    // Hiển thị danh sách người dùng
    public function index()
    {
        $users = $this->userRepository->getAllUsers(); // Sử dụng repository để lấy tất cả người dùng
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles')); // Truyền danh sách vai trò khi tạo người dùng
    }

    // Hiển thị chi tiết người dùng
    public function show($id)
    {
        $user = $this->userRepository->getUserById($id); // Sử dụng repository để lấy người dùng theo ID
        return view('users.show', compact('user'));
    }

    // Chỉnh sửa thông tin người dùng
    public function edit($id)
    {
        $user = $this->userRepository->getUserById($id); // Lấy người dùng từ repository
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $this->userRepository->updateUser($id, $request->name, $request->email, $request->role_id); // Cập nhật người dùng thông qua repository
        return redirect()->route('users.index')->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    // Xóa tài khoản người dùng
    public function destroy($id)
    {
        $this->userRepository->deleteUser($id); // Xóa người dùng thông qua repository
        return redirect()->route('users.index')->with('success', 'Người dùng đã bị xóa.');
    }

    // Thêm người dùng
    public function store(Request $request)
    {
        // Xác thực dữ liệu đầu vào
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,author,reader', // Xác nhận vai trò hợp lệ
        ]);

        // Tạo người dùng mới thông qua repository
        $this->userRepository->addUser(
            $request->name,
            $request->email,
            Role::where('name', $request->role)->first()->id // Lấy role_id từ bảng roles
        );

        return redirect()->route('users.index')->with('success', 'Người dùng đã được thêm thành công!');
    }

    // Phân quyền người dùng
    // public function assignRole(Request $request, $id)
    // {
    //     $this->userRepository->assignRole($id, $request->role_id); // Gọi repository để phân quyền cho người dùng
    //     return redirect()->route('users.index')->with('success', 'Vai trò đã được gán thành công.');
    // }
}
