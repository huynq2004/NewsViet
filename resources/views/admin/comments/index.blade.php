<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách bình luận</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Danh sách bình luận</h1>
    <a href="{{ route('admin.comments.create') }}" class="btn btn-primary">Thêm bình luận</a>
    <a href="{{ route('admin.comments.pending') }}" class="btn btn-warning">Bình luận chưa duyệt</a>
    <a href="{{ route('admin.comments.reported') }}" class="btn btn-danger">Bình luận bị báo cáo</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nội dung</th>
                <th>Bài viết</th>
                <th>Người dùng</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->article->title }}</td>
                    <td>{{ $comment->user->name }}</td>
                    <td>
                        <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                        <form action="{{ route('admin.comments.report', $comment->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Báo cáo vi phạm</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
