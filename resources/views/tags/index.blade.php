{{-- View hiển thị danh sách tất cả các hashtag có trong hệ thống --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Danh sách Hashtag</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Hashtag</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
            <tr>
                <td>{{ $tag->id }}</td>
                <td>{{ $tag->name }}</td>
                <td>
                    <a href="{{ route('tags.edit', $tag->id) }}" class="btn btn-warning">Sửa</a>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('tags.create') }}" class="btn btn-primary">Thêm Hashtag</a>
</div>
@endsection
