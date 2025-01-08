@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Bình luận mới nhất</h2>

    @if($latestComment)
    <div class="latest-comment">
        <p><strong>{{ $latestComment->user->name }}:</strong> {{ $latestComment->content }}</p>
        <small>Đăng lúc: {{ $latestComment->created_at }}</small>
        <a href="{{ route('comments.edit', $latestComment->id) }}" class="btn btn-sm btn-primary">Sửa</a>
    </div>
    @else
    <p>Không có bình luận nào.</p>
    @endif
</div>
@endsection