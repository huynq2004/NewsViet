<!-- resources/views/author/create.blade.php -->
{{-- form tạo bài viết mớimới --}}
@extends('layouts.app')

@section('content')
    <h2>Thêm bài viết mới</h2>
    <form method="POST" action="{{ route('author.store') }}" enctype="multipart/form-data">
        @csrf
        <label for="title">Tiêu đề:</label>
        <input type="text" name="title" required>

        <label for="content">Nội dung:</label>
        <textarea name="content" required></textarea>

        <label for="category_id">Danh mục:</label>
        <select name="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <label for="tags">Tags:</label>
        <select name="tags[]" multiple>
            @foreach($tags as $tag)
                <option value="{{ $tag->id }}">{{ $tag->name }}</option>
            @endforeach
        </select>

        <label for="image">Ảnh đại diện:</label>
        <input type="file" name="image">

        <button type="submit">Tạo bài viết</button>
    </form>
@endsection
