<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Hashtags</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
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
                        <h1 class="h3">Danh sách Hashtags</h1>
                        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Thêm Hashtag</a>
                    </div>

                    <!-- Hiển thị thông báo thành công -->
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Bảng danh sách Hashtags -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Tên Hashtag</th>
                                    <th>Số bài viết</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tags as $tag)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $tag->name }}</td>
                                    <td>{{ $tag->articles_count }}</td>
                                    <td class="text-center">
                                        <!-- Các nút chỉnh sửa/xóa -->
                                        <a href="{{ route('admin.tags.show', $tag->id) }}" class="btn btn-info btn-sm">Xem</a>
                                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bạn có chắc muốn xóa hashtag này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có hashtag nào</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $tags->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
