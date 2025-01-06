use newsviet;

--Trigger 1: Tự động cập nhật cột updated_at của bảng tags mỗi khi một hashtag được chỉnh sửa.
--Mục đích: Đảm bảo thông tin được cập nhật chính xác và đồng nhất.
--Duyệt qua từng bản ghi trong inserted để cập nhật thời gian chính xác cho từng hashtag được chỉnh sửa.
CREATE TRIGGER trg_UpdateTagWithCursor
ON tags
AFTER UPDATE
AS BEGIN
    -- Con trỏ để duyệt qua các hashtag được chỉnh sửa
    DECLARE cur_updated_tags CURSOR FOR
    SELECT id FROM inserted;
    DECLARE @TagID INT;
    OPEN cur_updated_tags;
    FETCH NEXT FROM cur_updated_tags INTO @TagID;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Cập nhật thời gian updated_at cho từng hashtag
        UPDATE tags
        SET updated_at = GETDATE()
        WHERE id = @TagID;
        PRINT N'Cập nhật thời gian cho hashtag ID: ' + CAST(@TagID AS NVARCHAR(10));
        FETCH NEXT FROM cur_updated_tags INTO @TagID;
    END

    CLOSE cur_updated_tags;
    DEALLOCATE cur_updated_tags;
END;

--test
UPDATE tags SET name = 'hehehe' WHERE id = 4;

UPDATE tags SET name = 'hahahihihehe' WHERE id =2;

drop trigger trg_UpdateTagWithCursor

-- Trigger 2: Xóa các bản ghi liên quan trong bảng article_tag nếu một hashtag bị xóa.
-- Mục đích: Duy trì tính toàn vẹn dữ liệu khi một hashtag không còn tồn tại.
CREATE TRIGGER trg_DeleteTag
ON tags
AFTER DELETE
AS
BEGIN
    -- Xóa các liên kết trong bảng article_tag khi tag bị xóa
    DELETE FROM article_tag
    WHERE tag_id IN (SELECT id FROM DELETED);
END;

drop trigger trg_DeleteTag

--View 1: Hiển thị danh sách bài viết theo hastag. Dùng để lọc các bài viết có liên kết với một hastag cụ thể.
CREATE VIEW View_ArticlesByTag AS
SELECT
    t.name AS tag_name,
    a.title AS article_title,
    a.content AS article_content,
    a.created_at AS article_created_at
FROM tags t
INNER JOIN
    article_tag at ON t.id = at.tag_id
INNER JOIN
    articles a ON at.article_id = a.id;

SELECT * FROM View_ArticlesByTag;
drop view View_ArticlesByTag

--View 2: Hiển thị danh sách tất cả hastag có trong hệ thống.
create VIEW View_AllHashtags AS
SELECT id AS tag_id, name AS tag_nam
FROM tags;

SELECT * FROM View_AllHashtags;
drop view View_AllHashtags


--Procedure 1: Thêm hastag mới vào hệ thống.
create PROCEDURE AddNewHashtag
    @TagName NVARCHAR(255) -- Tên hashtag
AS
BEGIN
    IF EXISTS (SELECT * FROM tags WHERE name = @TagName)
    BEGIN
        PRINT N'Hashtag này đã tồn tại trong hệ thống.';
        RETURN;
    END

    INSERT INTO tags (name, created_at, updated_at)
    VALUES (@TagName, GETDATE(), GETDATE());

    PRINT N'Hashtag đã được thêm thành công.';
END;

EXEC AddNewHashtag @TagName = N'proc_addtag';
drop proc AddNewHashtag

--Procedure 2: Sửa hastag cho bài viết, cập nhật thông tin trong cơ sở dữ liệu.
CREATE PROCEDURE UpdateHashtagCursor
    @OldTagName NVARCHAR(255), @NewTagName NVARCHAR(255)
AS BEGIN
    -- Kiểm tra nếu hashtag cũ không tồn tại
    IF NOT EXISTS (SELECT * FROM tags WHERE name = @OldTagName)
    BEGIN
        PRINT N'Hashtag cũ không tồn tại.';
        RETURN;
    END

    -- Kiểm tra nếu hashtag mới đã tồn tại, nếu không thì thêm mới
    IF NOT EXISTS (SELECT * FROM tags WHERE name = @NewTagName)
    BEGIN
        INSERT INTO tags (name, created_at, updated_at)
        VALUES (@NewTagName, GETDATE(), GETDATE());
    END

    -- Lấy ID của hashtag cũ và mới
    DECLARE @OldTagID INT = (SELECT id FROM tags WHERE name = @OldTagName);
    DECLARE @NewTagID INT = (SELECT id FROM tags WHERE name = @NewTagName);

    -- Con trỏ để duyệt qua các bài viết liên kết với hashtag cũ
    DECLARE cur_articles CURSOR FOR
    SELECT article_id FROM article_tag WHERE tag_id = @OldTagID;
    DECLARE @ArticleID INT;
    OPEN cur_articles;
    FETCH NEXT FROM cur_articles INTO @ArticleID;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        UPDATE article_tag
        SET tag_id = @NewTagID
        WHERE article_id = @ArticleID AND tag_id = @OldTagID;
        PRINT N'Hashtag đã được cập nhật cho bài viết ID: ' + CAST(@ArticleID AS NVARCHAR(10));
        FETCH NEXT FROM cur_articles INTO @ArticleID;
    END

    CLOSE cur_articles;
    DEALLOCATE cur_articles;
    PRINT N'Tất cả các bài viết đã được cập nhật thành công.';
END;

EXEC UpdateHashtagCursor 'testproc1', '111';
drop proc UpdateHashtagCursor

--Function 1: Kiểm tra sự tồn tại của hastag trong hệ thống trước khi thêm hoặc sửa.
CREATE FUNCTION CheckHashtagExists(@hashtag_name VARCHAR(255))
RETURNS BIT
AS
BEGIN
    DECLARE @exists BIT;
    SELECT @exists = CASE
                        WHEN EXISTS (SELECT * FROM tags WHERE name = @hashtag_name)
                        THEN 1 ELSE 0
                     END;
    RETURN @exists;
END;

--gọi hàm
DECLARE @hashtag_name VARCHAR(255) = 'func1';
IF dbo.CheckHashtagExists(@hashtag_name) = 1
BEGIN
    PRINT N'Hashtag đã tồn tại.';
END
ELSE
BEGIN
    PRINT N'Hashtag không tồn tại. Có thể thêm mới.';
    INSERT INTO tags VALUES (@hashtag_name,GETDATE(), GETDATE());
END;

drop function CheckHashtagExists

--Function 2: Lấy danh sách bài viết theo hastag.
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

--gọi hàm
DECLARE @hashtag_name VARCHAR(20) = 'hehe';
SELECT * FROM GetArticlesByHashtag(@hashtag_name);

drop function GetArticlesByHashtag
