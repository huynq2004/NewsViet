<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TagObjects extends Migration
{
    public function up()
    {
        // View 1: Hiển thị danh sách bài viết theo hastag. Dùng để lọc các bài viết có liên kết với một hastag cụ thể.
        DB::unprepared("
            CREATE VIEW View_ArticlesByTag AS
            SELECT
                t.name AS tag_name,
                a.title AS article_title,
                a.content AS article_content,
                a.created_at AS article_created_at
            FROM tags t
            INNER JOIN article_tag at ON t.id = at.tag_id
            INNER JOIN articles a ON at.article_id = a.id;
        ");

        //View 2: Hiển thị danh sách tất cả hastag có trong hệ thống.
        DB::unprepared("
            Create VIEW View_AllHastags AS
            SELECT
                id AS tag_id,
                name AS tag_name
            FROM tags;
        ");

        // Trigger 1: Tự động cập nhật cột updated_at của bảng tags mỗi khi một hashtag được chỉnh sửa.
        DB::unprepared("
            CREATE TRIGGER trg_UpdateTagWithCursor
            ON tags AFTER UPDATE
            AS BEGIN
                DECLARE cur_updated_tags CURSOR FOR
                SELECT id FROM inserted;
                DECLARE @TagID INT;
                OPEN cur_updated_tags;
                FETCH NEXT FROM cur_updated_tags INTO @TagID;
                WHILE @@FETCH_STATUS = 0
                BEGIN
                    UPDATE tags SET updated_at = GETDATE()
                    WHERE id = @TagID;

                    PRINT N'Cập nhật thời gian cho hashtag ID: ' + CAST(@TagID AS NVARCHAR(10));

                    FETCH NEXT FROM cur_updated_tags INTO @TagID;
                END

                CLOSE cur_updated_tags;
                DEALLOCATE cur_updated_tags;
            END;
        ");

        // Trigger 2: Xóa các bản ghi liên quan trong bảng article_tag nếu một hashtag bị xóa.
        DB::unprepared("
            CREATE TRIGGER trg_DeleteTag
            ON tags AFTER DELETE
            AS BEGIN
                DELETE FROM article_tag
                WHERE tag_id IN (SELECT id FROM DELETED);
            END;
        ");

        // --Function 1: Kiểm tra sự tồn tại của hastag trong hệ thống trước khi thêm hoặc sửa.
        DB::unprepared("
            CREATE FUNCTION CheckHashtagExists(@hashtag_name VARCHAR(255))
            RETURNS BIT
            AS BEGIN
                DECLARE @exists BIT;
                SELECT @exists = CASE
                        WHEN EXISTS (SELECT * FROM tags WHERE name = @hashtag_name)
                        THEN 1 ELSE 0
                     END;
                RETURN @exists;
            END;
        ");

        // --Function 2: Lấy danh sách bài viết theo hastag.
        DB::unprepared("
            CREATE FUNCTION GetArticlesByHashtag(@hashtag_name VARCHAR(20))
            RETURNS TABLE
            AS
            RETURN
            (
                SELECT a.id AS article_id, a.title, a.content
                FROM articles a
                JOIN article_tag at ON a.id = at.article_id
                JOIN tags t ON at.tag_id = t.id
                WHERE t.name = @hashtag_name
            );
        ");

        // --Procedure 1: Thêm hastag mới vào hệ thống.
        DB::unprepared("
            Create PROCEDURE AddNewHashtag (@TagName NVARCHAR(255))
            AS BEGIN
                IF EXISTS (SELECT * FROM tags WHERE name = @TagName)
                BEGIN
                    PRINT N'Hashtag này đã tồn tại trong hệ thống.';
                    RETURN;
                END

                INSERT INTO tags (name, created_at, updated_at)
                VALUES (@TagName, GETDATE(), GETDATE());
                PRINT N'Hashtag đã được thêm thành công.';
            END;
        ");

        //--Procedure 2: Sửa hastag cho bài viết, cập nhật thông tin trong cơ sở dữ liệu.
        DB::unprepared("
            CREATE PROCEDURE UpdateHashtagCursor
            @OldTagName NVARCHAR(255), @NewTagName NVARCHAR(255)
            AS BEGIN
                IF NOT EXISTS (SELECT * FROM tags WHERE name = @OldTagName)
                BEGIN
                    PRINT N'Hashtag cũ không tồn tại.';
                    RETURN;
                END

                IF NOT EXISTS (SELECT * FROM tags WHERE name = @NewTagName)
                BEGIN
                    INSERT INTO tags (name, created_at, updated_at)
                    VALUES (@NewTagName, GETDATE(), GETDATE());
                END

                DECLARE @OldTagID INT = (SELECT id FROM tags WHERE name = @OldTagName);
                DECLARE @NewTagID INT = (SELECT id FROM tags WHERE name = @NewTagName);

                DECLARE cur_articles CURSOR FOR
                SELECT article_id FROM article_tag
                WHERE tag_id = @OldTagID;

                DECLARE @ArticleID INT;

                OPEN cur_articles;
                FETCH NEXT FROM cur_articles INTO @ArticleID;
                WHILE @@FETCH_STATUS = 0
                BEGIN
                    UPDATE article_tag SET tag_id = @NewTagID
                    WHERE article_id = @ArticleID AND tag_id = @OldTagID;
                    PRINT N'Hashtag đã được cập nhật cho bài viết ID: ' + CAST(@ArticleID AS NVARCHAR(10));

                    FETCH NEXT FROM cur_articles INTO @ArticleID;
                END

                CLOSE cur_articles; DEALLOCATE cur_articles;

                PRINT N'Tất cả các bài viết đã được cập nhật thành công.';
            END;
        ");

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa View
        DB::unprepared("DROP VIEW IF EXISTS View_ArticlesByTag");
        DB::unprepared("DROP VIEW IF EXISTS View_AllHastags");

        // Xóa Trigger
        DB::unprepared("DROP TRIGGER IF EXISTS trg_UpdateTagWithCursor");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_DeleteTag");

        // Xóa Function
        DB::unprepared("DROP FUNCTION IF EXISTS CheckHashtagExists");
        DB::unprepared("DROP FUNCTION IF EXISTS GetArticlesByHashtag");

        // Xóa Procedure
        DB::unprepared("DROP PROCEDURE IF EXISTS AddNewHashtag");
        DB::unprepared("DROP PROCEDURE IF EXISTS UpdateHashtagCursor");

        // Xóa Cursor (trong procedure)
    }
};
