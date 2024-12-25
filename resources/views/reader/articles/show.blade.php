<!-- resources/views/reader/show.blade.php -->
{{-- Xem chi tiết bài viếtviết --}}
@extends('layouts.app')

@section('content')
    <h2>{{ $article->title }}</h2>
    <p><strong>Tác giả:</strong> {{ $article->author->name }}</p>
    <p><strong>Danh mục:</strong> {{ $article->category->name }}</p>
    <p>{{ $article->content }}</p>

    <div class="comments">
        <h3>Comments</h3>
        @foreach($article->comments as $comment)
            <div class="comment">
                <p><strong>{{ $comment->user->name }}:</strong> {{ $comment->content }}</p>
            </div>
        @endforeach
    </div>
@endsection
