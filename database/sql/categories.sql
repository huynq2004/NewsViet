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

SELECT dbo.fn_total_articles(1) AS total_articles;

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

SELECT * FROM dbo.fn_get_subcategories(1);

-- Thủ tục
-- 1. Xóa danh mục và toàn bộ danh mục con
CREATE PROCEDURE sp_delete_category_with_children
    @category_id INT
AS
BEGIN
    DECLARE @child_id INT;

    -- Mở con trỏ để lấy danh mục con
    DECLARE category_cursor CURSOR LOCAL FOR
        SELECT id FROM categories WHERE parent_id = @category_id;

    OPEN category_cursor;
    FETCH NEXT FROM category_cursor INTO @child_id;

    -- Xử lý danh mục con
    WHILE @@FETCH_STATUS = 0
    BEGIN
        -- Gọi đệ quy để xóa danh mục con
        EXEC sp_delete_category_with_children @child_id;
        FETCH NEXT FROM category_cursor INTO @child_id;
    END

    CLOSE category_cursor;
    DEALLOCATE category_cursor;

    -- Cập nhật parent_id của các danh mục con thành NULL nếu có
    UPDATE categories
    SET parent_id = NULL
    WHERE parent_id = @category_id;

    -- Xóa danh mục
    DELETE FROM categories WHERE id = @category_id;
END;


-- Gọi thủ tục để xóa danh mục với id là 1 và các danh mục con
EXEC sp_delete_category_with_children @category_id = 1;


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


-- Chuyển các danh mục con của danh mục có id = 2 sang danh mục cha mới có id =3
EXEC sp_move_subcategories @old_parent_id = 2, @new_parent_id = 3;

-- Trigger
-- 1. Đặt id danh mục các bài viết thành NULL khi danh mục bị xóa
CREATE TRIGGER trg_after_delete_category
ON categories
AFTER DELETE
AS
BEGIN
    -- Vô hiệu hóa ràng buộc khóa ngoại tạm thời
    ALTER TABLE articles NOCHECK CONSTRAINT articles_category_id_foreign;

    -- Cập nhật các bản ghi con, gán parent_id thành NULL
    UPDATE articles
    SET category_id = NULL
    WHERE category_id IN (SELECT id FROM DELETED);

    -- Kích hoạt lại ràng buộc khóa ngoại
    ALTER TABLE articles CHECK CONSTRAINT articles_category_id_foreign;
END;

-- 2. Đặt parent_id của các danh mục con thành NULL khi danh mục cha bị xóa
CREATE TRIGGER trg_instead_of_delete_category
ON categories
INSTEAD OF DELETE
AS
BEGIN
    -- Vô hiệu hóa ràng buộc khóa ngoại tạm thời
    ALTER TABLE categories NOCHECK CONSTRAINT categories_parent_id_foreign;

    -- Cập nhật các bản ghi con, gán parent_id thành NULL
    UPDATE categories
    SET parent_id = NULL
    WHERE parent_id IN (SELECT id FROM DELETED);

    -- Kích hoạt lại ràng buộc khóa ngoại
    ALTER TABLE categories CHECK CONSTRAINT categories_parent_id_foreign;
    DELETE FROM categories
    WHERE id IN (SELECT id FROM DELETED);
END;


-- Giả sử xóa danh mục có id là 3
DELETE FROM categories WHERE id = 3;
SELECT * FROM categories WHERE parent_id IS NULL;

-- View
-- 1. Hiển thị danh mục cùng tổng số bài viết
CREATE VIEW vw_categories_with_total_articles AS
SELECT 
    id AS category_id,
    name AS category_name,
    parent_id,
    dbo.fn_total_articles(id) AS total_articles
FROM categories;


-- Kiểm tra view `vw_categories_with_total_articles`
SELECT * FROM vw_categories_with_total_articles;

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

-- Kiểm tra view `vw_category_tree`
SELECT * FROM vw_category_tree;
