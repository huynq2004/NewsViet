@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách bình luận</h2>

    <!-- Hiển thị danh sách bình luận -->
    <div class="comments-list">
        @foreach($comments as $comment)
        <div class="comment-item">
            <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
            <small>Đăng lúc: {{ $comment->created_at }}</small>
            <a href="{{ route('comments.edit', $comment->id) }}" class="btn btn-sm btn-primary">Sửa</a>
            <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
            </form>
        </div>
        @endforeach
    </div>

    <!-- Form thêm bình luận -->
    <form action="{{ route('comments.store', ['articleId' => $articleId]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="content">Thêm bình luận:</label>
            <textarea name="content" id="content" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Gửi</button>
    </form>
</div>
@endsection