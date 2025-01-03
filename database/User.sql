--Trigger 1 : Xóa thông tin người dùng và ghi lại lịch sử xóa
CREATE TRIGGER trg_delete_user
ON users
FOR DELETE
AS
BEGIN
    IF EXISTS (SELECT * FROM deleted WHERE role_id = 1)
    BEGIN
        PRINT 'Không được phép xóa Admin!';
        ROLLBACK;
        RETURN;
    END

    INSERT INTO deleted_users (user_id, user_name, user_email, deleted_at)
    SELECT id, name, email, GETDATE()
    FROM deleted;
    PRINT 'Thông tin người dùng đã được xóa .';
END;
--Tạo bảng để lưu lịch sử xóa
CREATE TABLE deleted_users (
    user_id INT,            
    user_name NVARCHAR(255), 
    user_email NVARCHAR(255),
    deleted_at DATETIME      
);

--Trigger 2: Thêm người dùng 

CREATE TRIGGER trg_insert_user
ON users
AFTER INSERT
AS
BEGIN
    DECLARE @user_name NVARCHAR(255),
	@user_email NVARCHAR(255),
	@user_role NVARCHAR(255);

    SELECT @user_name = i.name, @user_email = email, @user_role = r.name
    FROM inserted i
    INNER JOIN roles r ON i.role_id = r.id;
    PRINT 'Người dùng mới đã được thêm:';
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

--Proc 1: Thủ tục kiểm tra người dùng theo vai trò và in ra thông tin (cursor)

CREATE PROCEDURE check_users_by_role
    @role_name NVARCHAR(255) 
AS
BEGIN
    DECLARE @user_id INT;
    DECLARE @user_name NVARCHAR(255);
    DECLARE @user_email NVARCHAR(255);
    DECLARE @role_name_from_db NVARCHAR(255);

    DECLARE user_cursor CURSOR FOR
        SELECT u.id, u.name, u.email, r.name
        FROM users u
        INNER JOIN roles r ON u.role_id = r.id
        WHERE r.name = @role_name; 
    OPEN user_cursor;

    FETCH NEXT FROM user_cursor INTO @user_id, @user_name, @user_email, @role_name_from_db;
    WHILE @@FETCH_STATUS = 0
    BEGIN
        PRINT 'User: ' + @user_name + ', email: ' + @user_email ;
        FETCH NEXT FROM user_cursor INTO @user_id, @user_name, @user_email, @role_name_from_db;
    END

    CLOSE user_cursor;
    DEALLOCATE user_cursor;

    PRINT 'Đã kiểm tra tất cả người dùng với vai trò: ' + @role_name;
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
    PRINT 'Đối tượng update thành công!';
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

