<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class Commentbjects extends Migration
{
    public function up()
    {
        // Tạo View
        //View 1: vw_comments_with_user_info (Xem bình luận kèm thông tin người dùng)
        DB::unprepared("
            CREATE VIEW vw_comments_with_user_info AS
            SELECT c.id, c.content, u.name AS user_name, c.created_at
            FROM comments c
            INNER JOIN users u ON c.user_id = u.id;

        ");
        // View 2: vw_article_comment_counts (Xem số lượng bình luận theo từng bài viết)
        DB::unprepared("
            CREATE VIEW vw_article_comment_counts AS
            SELECT article_id, COUNT(*) AS comment_count
            FROM comments
            GROUP BY article_id;

        ");

        // Tạo Trigger
        // Trigger 1: tr_comments_update (Cập nhật updated_at khi bình luận được cập nhật)
        DB::unprepared("
            ALTER TRIGGER tr_comments_update 
            ON comments
            AFTER UPDATE
            AS
            BEGIN
                UPDATE c
                SET updated_at = GETDATE()
                FROM comments c
                INNER JOIN inserted i ON c.id = i.id
                WHERE c.content <> i.content; -- Chỉ cập nhật nếu nội dung khác nhau
            END;
        ");

        //Trigger 2: Trigger tr_comments_set_created_at (Tự động gán thời gian tạo bình luận):
        DB::unprepared("
            CREATE TRIGGER tr_update_comment_approved
            ON comments
            AFTER UPDATE
            AS
            BEGIN
                IF EXISTS (SELECT 1 FROM inserted WHERE LEN(content) > 0) 
                BEGIN
                    SET created_at = GETDATE()
                    FROM comments c
                    INNER JOIN inserted i ON c.id = i.id
                    WHERE c.id = i.id;
                END
            END;
        ");


        // Tạo Function

        //UDF 1: fn_get_comments_with_keyword_cursor ( lấy danh sách các bình luận của một bài viết cụ thể có chứa từ khóa)
        DB::unprepared("
            CREATE FUNCTION fn_get_comments_with_keyword_cursor (@article_id INT, @keyword NVARCHAR(50))
            RETURNS @CommentsTable TABLE (
            id INT,
            content NVARCHAR(MAX),
            created_at DATETIME,
            updated_at DATETIME
            )
            AS
            BEGIN
                DECLARE @comment_id INT,
                    @content NVARCHAR(MAX),
                    @created_at DATETIME,
                    @updated_at DATETIME;

                DECLARE comment_cursor CURSOR LOCAL FOR
                SELECT id, content, created_at, updated_at
                FROM comments
                WHERE article_id = @article_id AND content LIKE '%' + @keyword + '%';

                OPEN comment_cursor;

                FETCH NEXT FROM comment_cursor INTO @comment_id, @content, @created_at, @updated_at;

                WHILE @@FETCH_STATUS = 0
                BEGIN
                    INSERT INTO @CommentsTable (id, content, created_at, updated_at)
                    VALUES (@comment_id, @content, @created_at, @updated_at);

                    FETCH NEXT FROM comment_cursor INTO @comment_id, @content, @created_at, @updated_at;
                END;

                CLOSE comment_cursor;
                DEALLOCATE comment_cursor;

                RETURN;
                END;
        ");

        //UDF 2: fn_get_latest_comment (Lấy bình luận mới nhất của một bài viết):
        DB::unprepared("
            CREATE FUNCTION fn_get_latest_comment (@article_id INT)
            RETURNS NVARCHAR(MAX)
            AS
            BEGIN
                DECLARE @latest_comment NVARCHAR(MAX);

                SELECT TOP 1 @latest_comment = content
                FROM comments
                WHERE article_id = @article_id
                ORDER BY created_at DESC;

            RETURN @latest_comment;
            END;
        ");

        // Tạo Procedure
        // Proc 1: sp_get_comments_by_article (Lấy bình luận theo bài viết)
        DB::unprepared("
            CREATE PROCEDURE sp_get_comments_by_article_with_cursor (@article_id INT)
            AS
            BEGIN
                DECLARE @comment_content NVARCHAR(MAX);
                DECLARE @comment_id INT; -- Để lấy cả ID cho ví dụ này
                DECLARE @created_at DATETIME;
                DECLARE @updated_at DATETIME;

                DECLARE comment_cursor CURSOR LOCAL FOR
                SELECT id, content, created_at, updated_at
                FROM comments
                WHERE article_id = @article_id;

                OPEN comment_cursor;

                FETCH NEXT FROM comment_cursor INTO @comment_id, @comment_content, @created_at, @updated_at;

                WHILE @@FETCH_STATUS = 0
                BEGIN
                    PRINT N'ID: ' + CAST(@comment_id AS NVARCHAR(10)) + N', Nội dung: ' + @comment_content + N', Tạo lúc: ' + CAST(@created_at AS NVARCHAR(20)) + N', Cập nhật lúc: ' + ISNULL(CAST(@updated_at AS NVARCHAR(20)), N'Chưa cập nhật');
                    FETCH NEXT FROM comment_cursor INTO @comment_id, @comment_content, @created_at, @updated_at;
                END;        
                CLOSE comment_cursor;
                DEALLOCATE comment_cursor;
            END;
        ");

        // Proc 2: sp_update_comments_by_keyword (Cập nhật bình luận theo từ khóa)
        DB::unprepared("
            CREATE PROCEDURE sp_update_comments_by_keyword
            @keyword NVARCHAR(255),    
            @new_content NVARCHAR(MAX) 
            AS
            BEGIN
                UPDATE comments
                SET content = @new_content
                WHERE content LIKE '%' + @keyword + '%'; -- Tìm bình luận có chứa từ khóa

                SELECT @@ROWCOUNT AS RowsAffected;
            END;
        ");

        // Tạo Cursor (trong stored procedure)
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa View
        DB::unprepared("DROP VIEW IF EXISTS vw_comments_with_user_info");
        DB::unprepared("DROP VIEW IF EXISTS vw_article_comment_counts");
        // Xóa Trigger
        DB::unprepared("DROP TRIGGER IF EXISTS tr_comments_update ");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_update_comment_approved");
        // Xóa Function
        DB::unprepared("DROP FUNCTION IF EXISTS fn_get_comments_with_keyword_cursor");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_get_latest_comment");
        // Xóa Procedure
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_comments_by_article_with_cursor");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_update_comments_by_keyword");
        // Xóa Cursor (trong procedure)
    }
};