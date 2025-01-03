<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    // Thêm người dùng mới
    public function addUser($name, $email, $role_id)
    {
        DB::table('users')->insert([
            'name' => $name,
            'email' => $email,
            'role_id' => $role_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Cập nhật thông tin người dùng
    public function updateUser($user_id, $name = null, $email = null, $role_id = null)
    {
        DB::select('EXEC update_user ?, ?, ?, ?', [$user_id, $name, $email, $role_id]);
    }

    // Kiểm tra vai trò người dùng
    public function getUserRole($user_id)
    {
        return DB::select('SELECT dbo.get_user_role(?) AS role', [$user_id]);
    }

    // Kiểm tra người dùng theo vai trò
    public function checkUsersByRole($role_name)
    {
        DB::select('EXEC check_users_by_role ?', [$role_name]);
    }

    // Xóa người dùng 
    public function deleteUser($user_id)
    {
        DB::table('users')->where('id', $user_id)->delete();
    }

    public function getAllUsers()
    {
        return DB::table('user_details')->get(); // Sử dụng view user_details
    }

    public function getUsersByRoleGroupedByUpdatedAt($role_name)
    {
        return DB::table('users_by_role_and_updated_at')
            ->where('role_name', $role_name)
            ->orderBy('updated_at')
            ->get();  // Sử dụng view users_by_role_and_updated_at
    }
}