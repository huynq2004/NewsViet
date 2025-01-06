<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Lệnh Artisan để mã hóa lại mật khẩu
Artisan::command('encrypt-passwords', function () {
    // Lấy tất cả người dùng từ bảng users
    $users = DB::table('users')->get();

    foreach ($users as $user) {
        // Kiểm tra xem mật khẩu đã mã hóa hay chưa (Bcrypt bắt đầu với "$2y$")
        if (strlen($user->password) !== 60 || substr($user->password, 0, 4) !== '$2y$') {
            // Nếu chưa, mã hóa mật khẩu
            $hashedPassword = Hash::make($user->password);

            // Cập nhật lại mật khẩu trong cơ sở dữ liệu
            DB::table('users')
                ->where('id', $user->id)
                ->update(['password' => $hashedPassword]);

            $this->info("Mật khẩu của người dùng ID {$user->id} đã được mã hóa.");
        } else {
            $this->info("Mật khẩu của người dùng ID {$user->id} đã được mã hóa trước đó.");
        }
    }

    $this->info('Mã hóa lại mật khẩu hoàn tất!');
});
