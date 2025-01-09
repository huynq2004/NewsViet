use newsviet


-- Trigger 1: tr_comments_update (Cập nhật updated_at khi bình luận được cập nhật)

create TRIGGER tr_comments_update 
ON comments
AFTER UPDATE
AS
BEGIN
    UPDATE c
    SET updated_at = GETDATE()
    FROM comments c
    INNER JOIN inserted i ON c.id = i.id
    WHERE c.content <> i.content; 
END;

---chẹck
UPDATE comments
SET content = N'test trigheere'
WHERE id = 3;
select * from comments

drop TRIGGER tr_comments_update  



-- Trigger 2: Trigger tr_comments_set_created_at (Tự động gán thời gian tạo bình luận)
CREATE TRIGGER tr_update_comment_approved
ON comments
AFTER UPDATE
AS
BEGIN

    IF EXISTS (SELECT 1 FROM inserted WHERE LEN(content) > 0) 
    BEGIN

        UPDATE comments
        SET created_at = GETDATE()
        FROM comments c
        INNER JOIN inserted i ON c.id = i.id
        WHERE c.id = i.id;
    END
END;

UPDATE comments
SET content = 'thay đổi'
WHERE id = 11; 

SELECT * FROM comments 

drop TRIGGER tr_update_comment_approved






--UDF 1: fn_get_comments_with_keyword_cursor ( lấy danh sách các bình luận của một bài viết cụ thể có chứa từ khóa)
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
GO
SELECT * FROM dbo.fn_get_comments_with_keyword_cursor(1, N'từ khóa cần tìm'); 

drop  FUNCTION fn_get_comments_with_keyword_cursor


--UDF 2: fn_get_latest_comment (Lấy bình luận mới nhất của một bài viết)
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

SELECT dbo.fn_get_latest_comment(1);

drop FUNCTION fn_get_latest_comment

--View 1: vw_comments_with_user_info (Xem bình luận kèm thông tin người dùng)
CREATE VIEW vw_comments_with_user_info AS
SELECT c.id, c.content, u.name AS user_name, c.created_at
FROM comments c
INNER JOIN users u ON c.user_id = u.id;

SELECT * FROM vw_comments_with_user_info;

drop VIEW vw_comments_with_user_info

-- View 2: vw_article_comment_counts (Xem số lượng bình luận theo từng bài viết)
CREATE VIEW vw_article_comment_counts AS
SELECT article_id, COUNT(*) AS comment_count
FROM comments
GROUP BY article_id;

SELECT * FROM vw_article_comment_counts;

drop VIEW vw_article_comment_counts


--Proc 1: sp_get_comments_by_article (Lấy bình luận theo bài viết)
Stored procedure này nhận vào article_id và trả về tất cả các bình 
luận thuộc bài viết đó. Nó tương tự như UDF fn_get_comments_by_article 
nhưng được triển khai dưới dạng stored procedure.


CREATE PROCEDURE sp_get_comments_by_article_with_cursor (@article_id INT)
AS
BEGIN
    DECLARE @comment_content NVARCHAR(MAX);
    DECLARE @comment_id INT; 
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


EXEC dbo.sp_get_comments_by_article_with_cursor @article_id = 11;

drop PROCEDURE sp_get_comments_by_article_with_cursor


-- Proc 2: sp_update_comments_by_keyword (Cập nhật bình luận theo từ khóa):
CREATE PROCEDURE sp_update_comments_by_keyword
    @keyword NVARCHAR(255),    
    @new_content NVARCHAR(MAX) 
AS
BEGIN
    UPDATE comments
    SET content = @new_content
    WHERE content LIKE '%' + @keyword + '%'; 

    SELECT @@ROWCOUNT AS RowsAffected;
END;

EXEC sp_update_comments_by_keyword
    @keyword = 'tốt',       
    @new_content = 'Bình luận này đã được cập nhật!'; 
SELECT * FROM comments
WHERE content LIKE '%Bình luận này đã được cập nhật%';

select *from comments

drop PROCEDURE sp_update_comments_by_keyword





