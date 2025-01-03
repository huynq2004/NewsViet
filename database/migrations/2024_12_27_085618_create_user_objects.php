<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tạo Trigger 1: Xóa thông tin người dùng và ghi lại lịch sử xóa
        DB::unprepared('
            CREATE TRIGGER trg_delete_user
            ON users
            FOR DELETE
            AS
            BEGIN
                IF EXISTS (SELECT * FROM deleted WHERE role_id = 1)
                BEGIN
                    PRINT "Không được phép xóa Admin!";
                    ROLLBACK;
                    RETURN;
                END

                INSERT INTO deleted_users (user_id, user_name, user_email, deleted_at)
                SELECT id, name, email, GETDATE()
                FROM deleted;
                PRINT "Thông tin người dùng đã được xóa.";
            END;
        ');

        // Tạo Trigger 2: Thêm người dùng
        DB::unprepared('
            CREATE TRIGGER trg_insert_user
            ON users
            AFTER INSERT
            AS
            BEGIN
                DECLARE @user_name NVARCHAR(255),
                        @user_email NVARCHAR(255),
                        @user_role NVARCHAR(255);

                SELECT @user_name = i.name, @user_email = email, @user_role = r.name
                FROM inserted i
                INNER JOIN roles r ON i.role_id = r.id;
                PRINT "Người dùng mới đã được thêm:";
            END;
        ');

        // Tạo Function 1: Lấy vai trò người dùng
        DB::unprepared('
            CREATE FUNCTION get_user_role(@user_id INT)
            RETURNS NVARCHAR(255)
            AS
            BEGIN
                DECLARE @role NVARCHAR(255);
                SELECT @role = name
                FROM roles
                WHERE id = (SELECT role_id FROM users WHERE id = @user_id);
                RETURN @role;
            END;
        ');

        // Tạo Function 2: Đếm tổng số người theo vai trò (cursor)
        DB::unprepared('
            CREATE FUNCTION count_users_by_role(@role_name NVARCHAR(255))
            RETURNS INT
            AS
            BEGIN
                DECLARE @role_id INT;
                DECLARE @count INT = 0;

                SELECT @role_id = id
                FROM roles
                WHERE name = @role_name;

                DECLARE user_cursor CURSOR FOR
                    SELECT u.id
                    FROM users u
                    INNER JOIN roles r ON u.role_id = r.id
                    WHERE r.id = @role_id;
                OPEN user_cursor;
                FETCH NEXT FROM user_cursor INTO @role_id; 
                WHILE @@FETCH_STATUS = 0
                BEGIN
                    SET @count = @count + 1;  
                    FETCH NEXT FROM user_cursor INTO @role_id;
                END

                CLOSE user_cursor;
                DEALLOCATE user_cursor;

                RETURN @count;
            END;
        ');

        // Tạo Procedure 1: Thủ tục kiểm tra người dùng theo vai trò và in ra thông tin (cursor)
        DB::unprepared('
            CREATE PROCEDURE check_users_by_role
                @role_name NVARCHAR(255) 
            AS
            BEGIN
                DECLARE @user_id INT;
                DECLARE @user_name NVARCHAR(255);
                DECLARE @user_email NVARCHAR(255);
                DECLARE @role_name_from_db NVARCHAR(255);

                DECLARE user_cursor CURSOR FOR
                    SELECT u.id, u.name, u.email, r.name
                    FROM users u
                    INNER JOIN roles r ON u.role_id = r.id
                    WHERE r.name = @role_name; 
                OPEN user_cursor;

                FETCH NEXT FROM user_cursor INTO @user_id, @user_name, @user_email, @role_name_from_db;
                WHILE @@FETCH_STATUS = 0
                BEGIN
                    PRINT "User: " + @user_name + ", email: " + @user_email ;
                    FETCH NEXT FROM user_cursor INTO @user_id, @user_name, @user_email, @role_name_from_db;
                END

                CLOSE user_cursor;
                DEALLOCATE user_cursor;

                PRINT "Đã kiểm tra tất cả người dùng với vai trò: " + @role_name;
            END;
        ');

        // Tạo Procedure 2: Chỉnh sửa người dùng
        DB::unprepared('
            CREATE PROCEDURE update_user
                @user_id INT,
                @name NVARCHAR(255) = NULL,
                @email NVARCHAR(255) = NULL,  
                @role_id INT = NULL
            AS
            BEGIN
                UPDATE users
                SET 
                    name = ISNULL(@name, name),
                    email = ISNULL(@email, email),  
                    role_id = ISNULL(@role_id, role_id),
                    updated_at = GETDATE()
                WHERE id = @user_id;
                PRINT "Đối tượng update thành công!";
            END;
        ');

        // Tạo View 1: Hiển thị thông tin danh sách người dùng
        DB::unprepared('
            CREATE VIEW user_details AS
            SELECT 
                u.id AS user_id,
                u.name AS user_name,
                u.email,
                r.name AS role_name,
                u.created_at,
                u.updated_at
            FROM users u
            JOIN roles r ON u.role_id = r.id;
        ');

        // Tạo View 2: Hiển thị người dùng theo vai trò và nhóm theo ngày cập nhật
        DB::unprepared('
            CREATE VIEW users_by_role_and_updated_at AS
            SELECT 
                u.id AS user_id,
                u.name AS user_name,
                u.email,
                r.name AS role_name,
                u.created_at,
                u.updated_at
            FROM users u
            JOIN roles r ON u.role_id = r.id;
        ');
    }

    public function down(): void
    {
        // Xóa Trigger 1
        DB::unprepared('DROP TRIGGER trg_delete_user;');

        // Xóa Trigger 2
        DB::unprepared('DROP TRIGGER trg_insert_user;');

        // Xóa Function 1
        DB::unprepared('DROP FUNCTION get_user_role;');

        // Xóa Function 2
        DB::unprepared('DROP FUNCTION count_users_by_role;');

        // Xóa Procedure 1
        DB::unprepared('DROP PROCEDURE check_users_by_role;');

        // Xóa Procedure 2
        DB::unprepared('DROP PROCEDURE update_user;');

        // Xóa View 1
        DB::unprepared('DROP VIEW user_details;');

        // Xóa View 2
        DB::unprepared('DROP VIEW users_by_role_and_updated_at;');
    }
};
