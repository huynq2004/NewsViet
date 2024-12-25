<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Hiển thị danh sách vai trò
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // Tạo vai trò mới
    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles',
            'description' => 'nullable|string',
        ]);

        Role::create($request->all());
        return redirect()->route('roles.index');
    }

    // Chỉnh sửa vai trò
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update($request->all());
        return redirect()->route('roles.index');
    }

    // Xóa vai trò
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
