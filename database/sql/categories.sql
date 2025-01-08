use newsviet;

-- Hàm
-- 1. Tính tổng số bài viết của một danh mục và các danh mục con
CREATE FUNCTION fn_total_articles(@category_id INT)
RETURNS INT
AS
BEGIN
    DECLARE @total INT = 0;

    -- Đệ quy để lấy toàn bộ danh mục con
    WITH RecursiveCategories AS (
        SELECT id
        FROM categories
        WHERE id = @category_id
        UNION ALL
        SELECT c.id
        FROM categories c
        JOIN RecursiveCategories rc ON c.parent_id = rc.id
    )
    -- Tính tổng số bài viết từ bảng articles
    SELECT @total = COUNT(*)
    FROM articles
    WHERE category_id IN (SELECT id FROM RecursiveCategories);

    RETURN @total;
END;


-- 2. Lấy danh sách danh mục con của một danh mục
CREATE FUNCTION fn_get_subcategories(@category_id INT)
RETURNS TABLE
AS
RETURN (
    WITH RecursiveCategories AS (
        SELECT id
        FROM categories
        WHERE id = @category_id
        UNION ALL
        SELECT c.id
        FROM categories c
        JOIN RecursiveCategories rc ON c.parent_id = rc.id
    )
    SELECT id FROM RecursiveCategories
);



-- Thủ tục
-- 1. Xóa danh mục và toàn bộ danh mục con
CREATE PROCEDURE sp_delete_category_with_children
    @category_id INT
AS
BEGIN
    DECLARE @child_id INT;
    DECLARE category_cursor CURSOR FOR
        SELECT id FROM categories WHERE parent_id = @category_id;

    OPEN category_cursor;
    FETCH NEXT FROM category_cursor INTO @child_id;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        EXEC sp_delete_category_with_children @child_id; -- Gọi đệ quy
        FETCH NEXT FROM category_cursor INTO @child_id;
    END

    CLOSE category_cursor;
    DEALLOCATE category_cursor;

    DELETE FROM categories WHERE id = @category_id;
END;

-- 2. Chuyển danh mục con sang danh mục cha mới
CREATE PROCEDURE sp_move_subcategories
    @old_parent_id INT,
    @new_parent_id INT
AS
BEGIN
    DECLARE @child_id INT;
    DECLARE category_cursor CURSOR FOR
        SELECT id FROM categories WHERE parent_id = @old_parent_id;

    OPEN category_cursor;
    FETCH NEXT FROM category_cursor INTO @child_id;

    WHILE @@FETCH_STATUS = 0
    BEGIN
        UPDATE categories
        SET parent_id = @new_parent_id
        WHERE id = @child_id;

        FETCH NEXT FROM category_cursor INTO @child_id;
    END

    CLOSE category_cursor;
    DEALLOCATE category_cursor;
END;



-- Trigger
-- 1. Tự động cập nhật parent_id về NULL khi danh mục cha bị xóa
CREATE TRIGGER trg_after_delete_category
ON categories
AFTER DELETE
AS
BEGIN
    UPDATE categories
    SET parent_id = NULL
    WHERE parent_id IN (SELECT id FROM DELETED);
END;

-- 2. Không cho phép xóa danh mục nếu còn bài viết thuộc danh mục đó
CREATE TRIGGER trg_prevent_delete_category_with_articles
ON categories
INSTEAD OF DELETE
AS
BEGIN
    IF EXISTS (
        SELECT 1 
        FROM articles 
        WHERE category_id IN (SELECT id FROM DELETED)
    )
    BEGIN
        RAISERROR ('Không thể xóa danh mục vì còn bài viết thuộc danh mục này.', 16, 1);
        ROLLBACK TRANSACTION;
    END
    ELSE
    BEGIN
        DELETE FROM categories
        WHERE id IN (SELECT id FROM DELETED);
    END
END;


-- View
-- 1. Hiển thị danh mục cùng tổng số bài viết
CREATE VIEW vw_categories_with_total_articles AS
SELECT 
    id AS category_id,
    name AS category_name,
    parent_id,
    dbo.fn_total_articles(id) AS total_articles
FROM categories;

-- 2. Hiển thị cây danh mục theo cấu trúc cha-con.
CREATE VIEW vw_category_tree AS
WITH RecursiveCategories AS (
    SELECT 
        id,
        name,
        parent_id,
        CAST(name AS NVARCHAR(MAX)) AS path
    FROM categories
    WHERE parent_id IS NULL
    UNION ALL
    SELECT 
        c.id,
        c.name,
        c.parent_id,
        CAST(rc.path + ' -> ' + c.name AS NVARCHAR(MAX)) AS path
    FROM categories c
    JOIN RecursiveCategories rc ON c.parent_id = rc.id
)
SELECT id, name, parent_id, path
FROM RecursiveCategories;

