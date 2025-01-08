<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Người dùng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container">
        <h1>Chi tiết người dùng</h1>

        <table class="table">
            <tr>
                <th>Tên người dùng</th>
                <td>{{ $user->user_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $user->email }}</td>
            </tr>
            <tr>
                <th>Vai trò</th>
                <td>{{ $user->role_name }}</td>
            </tr>
            <tr>
                <th>Ngày tạo</th>
                <td>{{ $user->created_at }}</td>
            </tr>
            <tr>
                <th>Ngày cập nhật</th>
                <td>{{ $user->updated_at }}</td>
            </tr>
        </table>

        <a href="{{ route('users.index') }}" class="btn btn-secondary">Trở lại</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>