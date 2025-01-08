--Trigger 1 : Xóa thông tin người dùng và ghi lại lịch sử xóa
CREATE TRIGGER trg_delete_user
ON users
AFTER DELETE
AS
BEGIN
    -- Kiểm tra nếu Admin bị xóa
    IF EXISTS (SELECT * FROM deleted WHERE role_id = 1)
    BEGIN
        PRINT (N'Không được phép xóa Admin!');
        ROLLBACK TRANSACTION;
        RETURN;
    END;

    -- Ghi thông tin người dùng bị xóa vào bảng deleted_users
    INSERT INTO deleted_users (user_id, user_name, user_email, deleted_at)
    SELECT id, name, email, GETDATE()
    FROM deleted;
END;


CREATE TABLE deleted_users (
    user_id INT,            
    user_name NVARCHAR(255), 
    user_email NVARCHAR(255),
    deleted_at DATETIME      
);

--trigg2 : 
CREATE TRIGGER trg_update_user_role
ON users
AFTER UPDATE
AS
BEGIN
    -- Chỉ chạy khi cột role_id được thay đổi
    IF UPDATE(role_id)
    BEGIN
        UPDATE users
        SET updated_at = GETDATE()
        WHERE id IN (SELECT id FROM inserted);
        
        PRINT 'Đã cập nhật thời gian sửa đổi do thay đổi vai trò người dùng.';
    END
END;


--Function 1 : Lấy vai trò người dùng

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

--Function 2 : Đếm tổng số người theo vai trò (cursor)

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

--Proc 1: Thủ tục THÊM người dùng mới mã hóa mật khẩu

CREATE PROCEDURE sp_insert_user
    @name NVARCHAR(255),
    @email NVARCHAR(255),
    @password NVARCHAR(255),
    @role_id INT
AS
BEGIN
    DECLARE @role_name NVARCHAR(255);
    DECLARE @hashed_password VARBINARY(64); -- Dùng để lưu mật khẩu băm

    -- Lấy tên vai trò (role name) dựa trên role_id
    SELECT @role_name = name
    FROM roles
    WHERE id = @role_id;

    IF @role_name IS NULL
    BEGIN
        PRINT 'Lỗi: Vai trò không tồn tại.';
        RETURN;
    END;

    -- Mã hóa mật khẩu bằng SHA2_256
    SET @hashed_password = HASHBYTES('SHA2_256', @password);

    INSERT INTO users (name, email, password, role_id, created_at, updated_at)
    VALUES (@name, @email, CONVERT(NVARCHAR(MAX), @hashed_password, 1), @role_id, GETDATE(), GETDATE());
    PRINT 'Người dùng mới đã được thêm: ' + @name + ' - ' + @email + ' - Vai trò: ' + @role_name;
END;

--Proc 2 : Chỉnh sửa người dùng
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

END;

--view 1: hiển thị thông tin danh sach nguoi dung
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
select *from user_details


--view 2: Hiển thị người dùng theo vai trò và nhóm theo ngày cập nhật
CREATE VIEW users_by_role_and_updated_at AS
SELECT 
    u.id AS user_id,
    u.name AS user_name,
    u.email,
    r.name AS role_name,
    u.created_at,
    u.updated_at
FROM users u
JOIN roles r ON u.role_id = r.id

-- Truy vấn từ view
SELECT * FROM users_by_role_and_updated_at
ORDER BY updated_at DESC;

