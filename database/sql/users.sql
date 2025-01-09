--trig1:xóa thông tin người dùng
CREATE TRIGGER trg_delete_user
ON users
AFTER DELETE
AS
BEGIN
    -- Kiểm tra nếu người dùng bị xóa có vai trò là Admin
    IF EXISTS (SELECT * FROM deleted WHERE role = 'Admin')
    BEGIN
        PRINT N'Không được phép xóa Admin!';
        ROLLBACK TRANSACTION; -- Hủy bỏ thao tác xóa
        RETURN;
    END;

    -- Nếu không phải Admin, cho phép xóa bình thường
    PRINT N'Người dùng đã bị xóa!';
END;

--trigg2 
-- Trigger cập nhật thời gian khi thay đổi vai trò người dùng
CREATE TRIGGER trg_update_user_role
ON users
AFTER UPDATE
AS
BEGIN
    -- Chỉ chạy khi cột role được thay đổi
    IF UPDATE(role)
    BEGIN
        UPDATE users
        SET updated_at = GETDATE()
        WHERE id IN (SELECT id FROM inserted);

        PRINT 'Đã cập nhật thời gian sửa đổi do thay đổi vai trò người dùng.';
    END
END;


UPDATE users
SET role = 'author'
WHERE id = 36; -- ID người dùng


--2.Function
--function1:Lấy vai trò của người dùng

CREATE FUNCTION get_user_role(@user_id INT)
RETURNS NVARCHAR(255)
AS
BEGIN
    DECLARE @role NVARCHAR(255);
    SELECT @role = role
    FROM users
    WHERE id = @user_id;
    RETURN @role;
END;

SELECT dbo.get_user_role(37) AS role_name;

--func2: đếm tổng số ng theo vai trò 
CREATE FUNCTION count_users_by_role(@role_name NVARCHAR(255))
RETURNS INT
AS
BEGIN
    DECLARE @count INT;

    SELECT @count = COUNT(*)
    FROM users
    WHERE role = @role_name;

    RETURN @count;
END;


drop proc count_users_by_role

select *from users
SELECT dbo.count_users_by_role('admin') AS total_users;

--3.Proc
--proc 1: thên người dùng và mã hóa mk
CREATE PROCEDURE sp_insert_user
    @name NVARCHAR(255),
    @email NVARCHAR(255),
    @password NVARCHAR(255),
    @role NVARCHAR(255)
AS
BEGIN
    DECLARE @hashed_password VARBINARY(64);

    -- Mã hóa mật khẩu
    SET @hashed_password = HASHBYTES('SHA2_256', @password);

    -- Chèn thông tin người dùng
    INSERT INTO users (name, email, password, role, created_at, updated_at)
    VALUES (@name, @email, CONVERT(NVARCHAR(MAX), @hashed_password, 1), @role, GETDATE(), GETDATE());

    PRINT 'Người dùng mới đã được thêm: ' + @name + ' - ' + @email + ' - Vai trò: ' + @role;
END;



select *from users
select *from roles

EXEC sp_insert_user 
    @name = 'Nguyen Van A', 
    @email = 'a11@gmail.com', 
    @password = 'password123',
@role_id = 3;

--proc2 : chỉnh sua nguoi dùng
CREATE PROCEDURE update_user
    @user_id INT,
    @name NVARCHAR(255) = NULL,
    @email NVARCHAR(255) = NULL,
    @role NVARCHAR(255) = NULL
AS
BEGIN
    UPDATE users
    SET 
        name = ISNULL(@name, name),
        email = ISNULL(@email, email),
        role = ISNULL(@role, role),
        updated_at = GETDATE()
    WHERE id = @user_id;

    PRINT 'Thông tin người dùng đã được cập nhật!';
END;



select *from users
EXEC update_user 
    @user_id = 17,           -- ID của người dùng cần cập nhật
    @name = 'New Name',     -- Tên mới (nếu muốn thay đổi)
    @email = 'newemail@example.com',  -- Email mới (nếu muốn thay đổi)
    @role_id = 2;           -- ID vai trò mới (nếu muốn thay đổi)

--4.View

--view 1: hiển thị thông tin danh sach nguoi dung
CREATE VIEW user_details AS
SELECT 
    id AS user_id,
    name AS user_name,
    email,
    role,
    created_at,
    updated_at
FROM users;


select *from user_details

--view 2: hiển thị thông tin về những người dùng đã hoạt động trong tháng vừa qua.
CREATE VIEW active_users_last_month AS
SELECT
    id AS user_id,
    name AS user_name,
    email,
    role,
    created_at,
    updated_at
FROM users
WHERE DATEDIFF(MONTH, updated_at, GETDATE()) <= 1;


--cursor 1 : Con trỏ xóa người dùng không hoạt động trong hơn 1 năm
DECLARE @user_id INT;
DECLARE inactive_users_cursor CURSOR FOR
    SELECT id
    FROM users
    WHERE DATEDIFF(YEAR, updated_at, GETDATE()) > 1;

OPEN inactive_users_cursor;

FETCH NEXT FROM inactive_users_cursor INTO @user_id;

WHILE @@FETCH_STATUS = 0
BEGIN
    DELETE FROM users WHERE id = @user_id;

    PRINT 'Người dùng với ID ' + CAST(@user_id AS NVARCHAR(10)) + ' đã bị xóa vì không hoạt động trong hơn 1 năm.';

    FETCH NEXT FROM inactive_users_cursor INTO @user_id;
END;

CLOSE inactive_users_cursor;
DEALLOCATE inactive_users_cursor;




--cursor 2:Duyệt qua tất cả người dùng
DECLARE @user_id INT;
DECLARE @user_name NVARCHAR(255);
DECLARE @user_email NVARCHAR(255);
DECLARE @user_role NVARCHAR(255);
DECLARE @user_created_at DATETIME;
DECLARE @user_updated_at DATETIME;


DECLARE all_users_cursor CURSOR FOR
    SELECT id, name, email, role, created_at, updated_at
    FROM users;

OPEN all_users_cursor;

FETCH NEXT FROM all_users_cursor INTO @user_id, @user_name, @user_email, @user_role, @user_created_at, @user_updated_at;

-- Duyệt qua từng người dùng và in thông tin ra
WHILE @@FETCH_STATUS = 0
BEGIN
    PRINT 'ID: ' + CAST(@user_id AS NVARCHAR(10)) + ', Tên: ' + @user_name + ', Email: ' + @user_email + ', Vai trò: ' + @user_role + ', Ngày tạo: ' + CAST(@user_created_at AS NVARCHAR(30)) + ', Ngày cập nhật: ' + CAST(@user_updated_at AS NVARCHAR(30));

    FETCH NEXT FROM all_users_cursor INTO @user_id, @user_name, @user_email, @user_role, @user_created_at, @user_updated_at;
END;

CLOSE all_users_cursor;
DEALLOCATE all_users_cursor;