<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CategoryObjects extends Migration
{
    public function up()
    {
        // Tạo View
        DB::unprepared("
            CREATE VIEW my_view AS
            SELECT users.id, users.name, posts.title
            FROM users
            JOIN posts ON users.id = posts.user_id
        ");

        // Tạo Trigger
        DB::unprepared("
            CREATE TRIGGER after_user_insert
            ON users
            AFTER INSERT
            AS
            BEGIN
                INSERT INTO logs (description, created_at) 
                VALUES ('User added', GETDATE());
            END
        ");

        // Tạo Function
        DB::unprepared("
            CREATE FUNCTION getUserCount()
            RETURNS INT
            AS
            BEGIN
                RETURN (SELECT COUNT(*) FROM users);
            END;
        ");

        // Tạo Procedure
        DB::unprepared("
            CREATE PROCEDURE getAllUsers
            AS
            BEGIN
                SELECT * FROM users;
            END;
        ");

        // Tạo Cursor (trong stored procedure)
        DB::unprepared("
            CREATE PROCEDURE iterateUsers
            AS
            BEGIN
                DECLARE userCursor CURSOR FOR SELECT id, name FROM users;
                OPEN userCursor;
                FETCH NEXT FROM userCursor;
                CLOSE userCursor;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa View
        DB::unprepared("DROP VIEW IF EXISTS my_view");

        // Xóa Trigger
        DB::unprepared("DROP TRIGGER IF EXISTS after_user_insert");

        // Xóa Function
        DB::unprepared("DROP FUNCTION IF EXISTS getUserCount");

        // Xóa Procedure
        DB::unprepared("DROP PROCEDURE IF EXISTS getAllUsers");

        // Xóa Cursor (trong procedure)
        DB::unprepared("DROP PROCEDURE IF EXISTS iterateUsers");
    }
};
