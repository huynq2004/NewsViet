@extends('layouts.author')

@section('content')
<div class="container mt-4">
    <h1>Quản lý Bài viết của bạn</h1>
    <a href="{{ route('author.articles.create') }}" class="btn btn-primary mb-3">Tạo bài viết mới</a>
    <table class="table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Danh mục</th>
                <th>Thời gian tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($articles as $article)
                <tr>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->category->name }}</td>
                    <td>{{ $article->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('author.articles.edit', $article->id) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('author.articles.destroy', $article->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
