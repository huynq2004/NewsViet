<!-- resources/views/author/index.blade.php -->
{{-- hiển thị danh sách bài viết của tác giảgiả --}}
@extends('layouts.app')

@section('content')
    <h2>Danh sách bài viết của bạn</h2>

    <div class="articles">
        @foreach($articles as $article)
            <div class="article">
                <h3><a href="{{ route('reader.show', $article->id) }}">{{ $article->title }}</a></h3>
                <a href="{{ route('author.edit', $article->id) }}">Sửa</a>
                <form method="POST" action="{{ route('author.destroy', $article->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Xóa</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
