@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Sửa bình luận</h2>

    <!-- Form sửa bình luận -->
    <form action="{{ route('comments.update', $comment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="content">Nội dung bình luận:</label>
            <textarea name="content" id="content" class="form-control" rows="3" required>{{ $comment->content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('comments.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection