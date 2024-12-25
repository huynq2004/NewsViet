{{-- View thêm hashtag --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm Hashtag</h1>
    <form action="{{ route('tags.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Tên Hashtag</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Lưu</button>
    </form>
</div>
@endsection
