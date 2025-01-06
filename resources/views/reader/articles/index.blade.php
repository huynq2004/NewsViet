@extends('reader.layout')

@section('content')
    <h2>Danh sách bài viết</h2>
    <ul>
        @foreach($articles as $article)
            <li><a href="{{ route('reader.articles.show', $article->id) }}">{{ $article->title }}</a></li>
        @endforeach
    </ul>
@endsection
