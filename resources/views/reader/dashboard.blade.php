@extends('layouts.reader')

@section('content')
    <h2>Dashboard của Người đọc</h2>
    <p>Chào mừng bạn đến với trang dashboard.</p>

    <h3>Danh sách bài viết</h3>
    <ul class="list-group">
        @foreach ($articles as $article)
            <li class="list-group-item">
                <a href="{{ route('reader.articles.show', $article->id) }}">
                    {{ $article->title }}
                </a>
            </li>
        @endforeach
    </ul>
@endsection 
