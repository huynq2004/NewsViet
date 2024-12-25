<!-- resources/views/author/edit.blade.php -->
{{-- form sửa bài viếtviết --}}
@extends('layouts.app')

@section('content')
    <h2>Sửa bài viết</h2>
    <form method="POST" action="{{ route('author.update', $article->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="title">Tiêu đề:</label>
        <input type="text" name="title" value="{{ $article->title }}" required>

        <label for="content">Nội dung:</label>
        <textarea name="content" required>{{ $article->content }}</textarea>

        <label for="category_id">Danh mục:</label>
        <select name="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $article->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
            @endforeach
        </select>

        <label for="tags">Tags:</label>
        <select name="tags[]" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}" {{ in_array($tag->id, $article->tags->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $tag->name }}</option>
            @endforeach
        </select>

        <label for="image">Ảnh đại diện:</label>
        <input type="file" name="image">

        <button type="submit">Cập nhật bài viết</button>
    </form>
@endsection
