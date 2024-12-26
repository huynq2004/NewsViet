@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Danh sách Hashtags</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.tags.create') }}" class="btn btn-primary">Thêm Hashtag</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Tên Hashtag</th>
                <th>Số bài viết</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tags as $tag)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->articles_count }}</td>
                    <td>
                        <a href="{{ route('admin.tags.show', $tag->id) }}" class="btn btn-info btn-sm">Xem</a>
                        <a href="{{ route('admin.tags.edit', $tag->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                        <form action="{{ route('admin.tags.destroy', $tag->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa hashtag này?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Không có hashtag nào</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $tags->links() }}
    </div>
</div>
@endsection
