@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Chỉnh sửa Hashtag</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Tên Hashtag</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tag->name) }}">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.tags.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
