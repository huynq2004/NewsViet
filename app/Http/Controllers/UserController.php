<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    // Hiển thị chi tiết người dùng
    public function show($id)
    {
        $user = $this->userRepository->getUserById($id);
        return view('admin.users.show', compact('user'));
    }


    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Cập nhật người dùng
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,author,reader',
        ]);

        $role = Role::where('name', $request->role)->first();
        $role_id = $role ? $role->id : null;

        // Call update in repository
        $this->userRepository->updateUser($id, $request->name, $request->email, $role_id);

        return redirect()->route('users.index')->with('success', 'Người dùng cập nhập thành công!');
    }

    public function destroy($id)
    {
        try {
            $this->userRepository->deleteUser($id); // Gọi repository để xóa người dùng
            return redirect()->route('users.index')->with('success', 'Người dùng đã bị xóa.');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Xác nhận mật khẩu
            'role' => 'required|in:admin,author,reader', // Vai trò phải hợp lệ
        ]);

        // Mã hóa mật khẩu
        $validated['password'] = Hash::make($request->password);

        // Tìm role_id từ bảng roles dựa trên giá trị role
        $role = Role::where('name', $request->role)->first();
        $validated['role_id'] = $role->id;

        // Lưu người dùng mới
        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được thêm.');
    }


    // Phân quyền người dùng
    // public function assignRole(Request $request, $id)
    // {
    //     $this->userRepository->assignRole($id, $request->role_id); // Gọi repository để phân quyền cho người dùng
    //     return redirect()->route('users.index')->with('success', 'Vai trò đã được gán thành công.');
    // }
}
