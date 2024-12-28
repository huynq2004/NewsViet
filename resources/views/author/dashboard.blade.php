@extends('layouts.author')

@section('title', 'Dashboard Tác giả')

@section('content')
    <div class="row">
        <div class="col-md-12">
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
@endsection
