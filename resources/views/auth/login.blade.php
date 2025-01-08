<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            display: flex;
            flex-direction: row;
            /* Sắp xếp theo hàng ngang */
            width: 100%;
            max-width: 900px;
            /* Giới hạn chiều rộng */
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .image-section {
            width: 50%;
            /* Chiếm 50% chiều rộng */
            background-color: #e9ecef;
            /* Màu nền nhẹ nếu ảnh không tải được */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .image-section img {
            max-width: 100%;
            max-height: 100%;

            /* Xoay ảnh 90 độ */
        }

        .login-form {
            width: 50%;
            /* Chiếm 50% chiều rộng */
            padding: 20px;
        }

        .login-form h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .form-group label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Phần ảnh logo -->
        <div class="image-section">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo của website">
        </div>

        <!-- Phần form đăng nhập -->
        <div class="login-form">
            <h1>Đăng nhập</h1>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="password" class="form-label">Mật khẩu:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>