<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                        <h1 class="h3">Danh mục</h1>
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Thêm danh mục</a>
                    </div>

                    <!-- Hiển thị flash message (nếu có) -->
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Tên danh mục</th>
                                <th>Mô tả</th>
                                <th>Danh mục con</th>
                                <th>Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $index => $category)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->description ?? 'Trống' }}</td>
                                <td>
                                    @if($category->children->isNotEmpty())
                                    <ul class="list-unstyled mb-0">
                                        @foreach($category->children as $child)
                                        <li>{{ $child->name }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <span class="text-muted">Không có danh mục con</span>
                                    @endif
                                </td>
                                <td class="d-flex gap-2">
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-info btn-sm">Sửa</a>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirmDelete()">
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
        // Xác nhận xóa danh mục
        function confirmDelete() {
            return confirm('Bạn có chắc muốn xóa danh mục này và toàn bộ danh mục con của nó không?');
        }
    </script>
</body>

</html>
