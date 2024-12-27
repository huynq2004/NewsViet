@extends('layouts.app')

@section('title', 'Chi tiết Hashtag')

@section('content')
<div class="container">
    <h1 class="mb-4">Thông tin chi tiết Hashtag</h1>

    <!-- Hiển thị thông tin của hashtag -->
    <div class="card mb-4">
        <div class="card-body">
            <h3 class="card-title">Tên Hashtag: {{ $tag->name }}</h3>
            <p class="card-text"><strong>Ngày tạo:</strong> {{ $tag->created_at->format('d/m/Y H:i:s') }}</p>
            <p class="card-text"><strong>Ngày cập nhật:</strong> {{ $tag->updated_at->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    <!-- Danh sách các bài viết liên quan -->
    <div class="card">
        <div class="card-header">
            <h4>Bài viết liên quan</h4>
        </div>
        <div class="card-body">
            @if($tag->articles->isEmpty())
                <p>Không có bài viết nào liên quan đến hashtag này.</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tiêu đề bài viết</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tag->articles as $key => $article)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $article->title }}</td>
                                <td>{{ $article->created_at->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-primary">Xem</a>
                                    <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
