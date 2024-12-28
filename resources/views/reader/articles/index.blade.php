@extends('layouts.reader')

@section('content')
    <div class="container mt-4">
        <h1>Các bài viết</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Danh mục</th>
                    <th>Ngày đăng</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td><a href="{{ route('reader.articles.show', $article->id) }}">{{ $article->title }}</a></td>
                        <td>{{ $article->category->name }}</td>
                        <td>{{ $article->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
