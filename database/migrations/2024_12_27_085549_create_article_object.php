<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ArticleObjects extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo Function 1: Lấy số lượng bài viết của tác giả
        DB::unprepared("
            CREATE FUNCTION fn_count_articles_by_author(@author_id INT)
            RETURNS INT
            AS
            BEGIN
                DECLARE @article_count INT;

                -- Tính số lượng bài viết của tác giả
                SELECT @article_count = COUNT(*)
                FROM articles
                WHERE author_id = @author_id;

                RETURN @article_count;
            END;
        ");

        // Tạo Function 2: Trả về các tag của bài viết
        DB::unprepared("
            CREATE FUNCTION fn_get_article_tags(@article_id INT)
            RETURNS VARCHAR(MAX)
            AS
            BEGIN
                DECLARE @tags VARCHAR(MAX);

                SELECT @tags = STRING_AGG(t.name, ', ')
                FROM tags t
                JOIN article_tag at ON t.id = at.tag_id
                WHERE at.article_id = @article_id;

                RETURN @tags;
            END;
        ");

        // Tạo View 1: Hiển thị danh sách bài viết với thông tin thẻ và thể loại
        DB::unprepared("
            CREATE VIEW vw_article_details AS
            SELECT a.id AS article_id, a.title, a.content, a.created_at, 
                   c.name AS category_name, t.name AS tag_name
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            LEFT JOIN article_tag at ON a.id = at.article_id
            LEFT JOIN tags t ON at.tag_id = t.id;
        ");

        // Tạo View 2: Hiển thị bài viết theo danh mục và tác giả
        DB::unprepared("
            CREATE VIEW view_articles_with_details AS
            SELECT 
                a.id AS article_id, 
                a.title, 
                c.name AS category_name, 
                u.name AS author_name, 
                a.created_at
            FROM articles a
            JOIN categories c ON a.category_id = c.id
            JOIN users u ON a.author_id = u.id;
        ");

        // Tạo Trigger 1: Xóa bài viết khi xóa người dùng 
        DB::unprepared("
            CREATE TRIGGER trg_delete_articles_when_user_deleted
            ON users
            AFTER DELETE
            AS
            BEGIN
                DELETE FROM articles
                WHERE author_id IN (SELECT id FROM deleted);
            END;
        ");

        // Tạo Trigger 2: Cập nhật thời gian sửa bài viết khi có thay đổi
        DB::unprepared("
            CREATE TRIGGER trg_update_article_time
            ON articles
            AFTER UPDATE
            AS
            BEGIN
                UPDATE articles
                SET updated_at = GETDATE()
                WHERE id IN (SELECT id FROM inserted);
            END;
        ");

        // Tạo Procedure 1: Thêm một bài viết mới
        DB::unprepared("
            CREATE PROCEDURE sp_add_article
                @title NVARCHAR(255),
                @content TEXT,
                @image NVARCHAR(255),
                @category_id INT,
                @author_id INT
            AS
            BEGIN
                INSERT INTO articles (title, content, image, category_id, author_id, created_at, updated_at)
                VALUES (@title, @content, @image, @category_id, @author_id, GETDATE(), GETDATE());
            END;
        ");

        // Tạo Procedure 2: Lấy danh sách các bài viết theo danh mục
        DB::unprepared("
            CREATE PROCEDURE sp_get_articles_by_category
                @category_id INT
            AS
            BEGIN
                SELECT id, title, content, image, created_at, updated_at
                FROM articles
                WHERE category_id = @category_id;
            END;
        ");

        // Tạo Procedure 3: Lấy danh sách bài viết theo con trỏ
        DB::unprepared("
            CREATE PROCEDURE FetchArticlesWithCursor
            AS
            BEGIN
                DECLARE @article_id INT;
                DECLARE @article_title NVARCHAR(255);
                DECLARE @article_content NVARCHAR(MAX);

                -- Khai báo con trỏ
                DECLARE article_cursor CURSOR FOR
                    SELECT id, title, content
                    FROM articles;

                -- Mở con trỏ
                OPEN article_cursor;

                -- Vòng lặp để đọc dữ liệu từ con trỏ
                FETCH NEXT FROM article_cursor INTO @article_id, @article_title, @article_content;

                WHILE @@FETCH_STATUS = 0
                BEGIN
                    -- Xử lý từng bài viết, ở đây sẽ hiển thị ra
                    PRINT 'ID: ' + CAST(@article_id AS NVARCHAR(10)) + ', Title: ' + @article_title;

                    -- Lấy bài viết tiếp theo
                    FETCH NEXT FROM article_cursor INTO @article_id, @article_title, @article_content;
                END

                -- Đóng và giải phóng con trỏ
                CLOSE article_cursor;
                DEALLOCATE article_cursor;
            END;
        ");

        // Tạo Procedure 4: Đếm số từ trong bài viết bằng con trỏ
        DB::unprepared("
            CREATE PROCEDURE CountTotalWordsInArticles
            AS
            BEGIN
                DECLARE @article_id INT;
                DECLARE @article_content NVARCHAR(MAX);
                DECLARE @total_word_count INT = 0;
                DECLARE @word_count INT;

                -- Kiểm tra xem con trỏ đã tồn tại hay chưa và đóng nếu cần thiết
                IF CURSOR_STATUS('global', 'article_cursor') >= -1
                BEGIN
                    CLOSE article_cursor;
                    DEALLOCATE article_cursor;
                END

                -- Khai báo con trỏ
                DECLARE article_cursor CURSOR FOR
                    SELECT id, content
                    FROM articles;

                -- Mở con trỏ
                OPEN article_cursor;

                -- Vòng lặp để đọc dữ liệu từ con trỏ
                FETCH NEXT FROM article_cursor INTO @article_id, @article_content;

                WHILE @@FETCH_STATUS = 0
                BEGIN
                    -- Đếm số từ trong nội dung bài viết
                    SET @word_count = (LEN(@article_content) - LEN(REPLACE(@article_content, ' ', '')) + 1);

                    -- Cộng dồn tổng số từ
                    SET @total_word_count = @total_word_count + @word_count;

                    -- Hiển thị thông tin của bài viết
                    PRINT 'Article ID: ' + CAST(@article_id AS NVARCHAR(10)) +
                          ', Word Count: ' + CAST(@word_count AS NVARCHAR(10));

                    -- Lấy bài viết tiếp theo
                    FETCH NEXT FROM article_cursor INTO @article_id, @article_content;
                END

                -- Đóng và giải phóng con trỏ
                CLOSE article_cursor;
                DEALLOCATE article_cursor;

                -- Hiển thị tổng số từ
                PRINT 'Total Word Count in All Articles: ' + CAST(@total_word_count AS NVARCHAR(10));
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các Function, View, Trigger, Procedure, Cursor nếu có
        DB::unprepared("DROP FUNCTION IF EXISTS fn_count_articles_by_author");
        DB::unprepared("DROP FUNCTION IF EXISTS fn_get_article_tags");
        DB::unprepared("DROP VIEW IF EXISTS vw_article_details");
        DB::unprepared("DROP VIEW IF EXISTS view_articles_with_details");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_delete_comments");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_update_article_time");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_add_article");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_get_articles_by_category");
        DB::unprepared("DROP PROCEDURE IF EXISTS FetchArticlesWithCursor");
        DB::unprepared("DROP PROCEDURE IF EXISTS CountTotalWordsInArticles");
    }
}
