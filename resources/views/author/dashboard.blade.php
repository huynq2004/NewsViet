<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Tác giả</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid d-flex vh-100 p-0">
        <div class="row flex-nowrap w-100">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white p-4">
                <h4>Dashboard Tác giả</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('author.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('author.articles.create') }}">Thêm bài viết</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('author.articles.index') }}">Danh sách bài viết</a>
                    </li>
                </ul>
                <hr class="text-white">
                <form action="{{ route('logout') }}" method="POST">
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
                <h3 class="mb-4">Bài viết của tôi</h3>
                <a href="{{ route('author.articles.create') }}" class="btn btn-success mb-3">Thêm bài viết mới</a>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tiêu đề</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('author.articles.edit', $article->id) }}" class="btn btn-primary">Sửa</a>
                                    <form action="{{ route('author.articles.destroy', $article->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
