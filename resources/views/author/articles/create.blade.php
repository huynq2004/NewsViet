@extends('layouts.author')

@section('content')
<div class="container mt-4">
    <h1>Tạo bài viết mới</h1>
    <form action="{{ route('author.articles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="title">Tiêu đề</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh mục</label>
            <select name="category_id" id="category_id" class="form-control" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="content">Nội dung</label>
            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <label for="image">Chọn ảnh</label>
            <input type="file" name="image" id="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary">Lưu bài viết</button>
    </form>
</div>
@endsection
