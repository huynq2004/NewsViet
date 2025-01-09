<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Bài viết</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .uncategorized {
            background-color: #f8f9fa;
            /* Màu xám nhạt */
            color: red;
            /* Màu chữ xám nhạt */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
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

            <!-- Main Content Area -->
            <div class="col-md-10">
                <div class="container py-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="h3">Quản lý Bài viết</h1>
                    </div>

                    <!-- Hiển thị flash message (nếu có) -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Bảng hiển thị bài viết -->
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Tiêu đề</th>
                                <th>Danh mục</th>
                                <th>Thời gian tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($articles as $article)
                            <tr class="{{ $article->category && $article->category->name ? 'uncategorized' : '' }}">
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->category->name ?? 'Uncategorized' }}</td>
                                <td>{{ $article->created_at ? $article->created_at->format('d/m/Y') : 'N/A' }}</td>
                                <td class="d-flex gap-2">
                                <a href="{{ route('reader.articles.show', $article->id) }}" class="btn btn-primary btn-sm">Xem</a>
                                    <!-- Admin chỉ có thể xóa -->
                                    <form action="{{ route('admin.articles.destroy', $article->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xác nhận xóa bài viết
        function confirmDelete() {
            return confirm('Bạn có chắc muốn xóa bài viết này?');
        }
    </script>
</body>

</html>