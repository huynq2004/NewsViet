@extends('layouts.reader')

@section('content')
    <div class="container mt-4">
        <h1>{{ $article->title }}</h1>
        <p><strong>Danh mục:</strong> {{ $article->category->name }}</p>
        <p>{{ $article->content }}</p>
        <p><strong>Ngày đăng:</strong> {{ $article->created_at->format('d/m/Y') }}</p>
    </div>
@endsection
