<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex vh-100">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white p-4 vh-100">
                <h4>Admin Dashboard</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.categories.index') }}">Danh mục</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.articles.index') }}">Bài viết</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.users.index') }}">Người dùng</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.tags.index') }}">Thẻ từ khóa</a>
                    </li>
                </ul>

                <!-- Nút Logout -->
                <form action="{{ route('logout') }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">Đăng xuất</button>
                </form>

                <!-- Thông tin dưới cùng -->
                <hr class="text-white">
                <h5>NewsViet</h5>
                <p>Contact: 012345678</p>
                <p>Address: 175 Tay Son Street</p>
                <p>Email: abc@gmail.com</p>
                <p>&copy; 2025 NewsViet</p>
            </div>



            <!-- Main Content -->
            <div class="col-md-10">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-primary">
                            <div class="card-header">Tổng số bài viết</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalArticles }}</h5>
                                <p class="card-text">Số lượng bài viết trên hệ thống.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-success">
                            <div class="card-header">Tổng số người dùng</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalUsers }}</h5>
                                <p class="card-text">Số lượng người dùng đã đăng ký.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-warning">
                            <div class="card-header">Tổng số danh mục</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalCategories }}</h5>
                                <p class="card-text">Số lượng danh mục đã tạo.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <div class="card text-white bg-info">
                            <div class="card-header">Tổng số thẻ từ khóa</div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $totalTags }}</h5>
                                <p class="card-text">Số lượng thẻ từ khóa hiện có.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>