<!-- resources/views/admin/index.blade.php -->
{{-- Quản lý bài viết(xóa bài viếtviết) --}}
@extends('layouts.app')

@section('content')
    <h2>Quản lý bài viết</h2>

    <div class="articles">
        @foreach($articles as $article)
            <div class="article">
                <h3><a href="{{ route('reader.show', $article->id) }}">{{ $article->title }}</a></h3>
                <form method="POST" action="{{ route('admin.destroy', $article->id) }}" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Xóa</button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
