<!-- resources/views/reader/index.blade.php -->
{{-- hiển thị danh sách bài viết cho ngừời đọc  --}}
@extends('layouts.app')

@section('content')
    <h2>Danh sách bài viết</h2>

    <form method="GET" action="{{ route('reader.search') }}">
        <input type="text" name="keyword" placeholder="Tìm kiếm bài viết..." required>
        <button type="submit">Tìm kiếm</button>
    </form>

    <div class="articles">
        @foreach($articles as $article)
            <div class="article">
                <h3><a href="{{ route('reader.show', $article->id) }}">{{ $article->title }}</a></h3>
                <p>By {{ $article->author->name }} | Category: {{ $article->category->name }}</p>
            </div>
        @endforeach
    </div>

    {{ $articles->links() }} <!-- Hiển thị phân trang -->
@endsection
