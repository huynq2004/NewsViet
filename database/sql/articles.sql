use newsviet;

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



--function 2: trả về các tag của bài viết 

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


---VIEW 1: VIEW HIỂN THỊ DANH SÁCH BÀI VIẾT VỚI THÔNG TIN tHẺ VÀ THỂ LỌAI 

CREATE VIEW vw_article_details AS
SELECT a.id AS article_id, a.title, a.content, a.created_at, 
       c.name AS category_name, t.name AS tag_name
FROM articles a
JOIN categories c ON a.category_id = c.id
LEFT JOIN article_tag at ON a.id = at.article_id
LEFT JOIN tags t ON at.tag_id = t.id;






--VIEW 2: HIỂN THỊ BÀI VIẾT THEO DANH MỤC VÀ TÁC GIẢ

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




-- Trigger 1: Xóa bài viết khi xóa người dùng
CREATE TRIGGER trg_delete_articles_when_user_deleted
ON users
AFTER DELETE
AS
BEGIN
    -- Xóa tất cả bài viết của người dùng đã bị xóa
    DELETE FROM articles
    WHERE author_id IN (SELECT id FROM deleted);
END;


--TRIGGER 2: CẬP NHẬT THỜI GIAN SỬA BÀI VIẾT KHI CÓ THAY ĐỔI 

CREATE TRIGGER trg_update_article_time
ON articles
AFTER UPDATE
AS
BEGIN
    UPDATE articles
    SET updated_at = GETDATE()
    WHERE id IN (SELECT id FROM inserted);
END;

--PROC 1: THÊM MỘT BÀI VIẾT MỚI
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


--PROC2: LẤY DANH SÁCH CÁC BÀI VIẾT THEO DANH MỤC 
CREATE PROCEDURE sp_get_articles_by_category
    @category_id INT
AS
BEGIN
    SELECT id, title, content, image, created_at, updated_at
    FROM articles
    WHERE category_id = @category_id;
END;



--cursor 1: Lấy danh sách bài viết theo con trỏ 
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


--cursor 2: đếm số từ trong bài viết bằng con trỏ 

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

