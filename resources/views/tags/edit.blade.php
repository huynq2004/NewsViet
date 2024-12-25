{{-- View sửa hashtag --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa Hashtag</h1>
    <form action="{{ route('tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Tên Hashtag</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $tag->name }}" required>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
</div>
@endsection
