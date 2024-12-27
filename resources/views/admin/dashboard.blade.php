<!-- Blade view: admin.dashboard -->
@extends('layouts.admin')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Card Quản lý Danh mục -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Quản lý Danh mục</h5>
                        <p class="card-text">Thêm, sửa, xóa danh mục bài viết.</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-primary">Truy cập</a>
                    </div>
                </div>
            </div>

            <!-- Card Quản lý Thẻ Tag -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Quản lý Thẻ Tag</h5>
                        <p class="card-text">Thêm, sửa, xóa thẻ tag cho bài viết.</p>
                        <a href="{{ route('admin.tags.index') }}" class="btn btn-primary">Truy cập</a>
                    </div>
                </div>
            </div>

            <!-- Card Quản lý Người dùng -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Quản lý Người dùng</h5>
                        <p class="card-text">Quản lý tài khoản người dùng trong hệ thống.</p>
                        <a href="#" class="btn btn-primary">Truy cập</a>
                    </div>
                </div>
            </div>

            <!-- Card Quản lý Bài viết -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Quản lý Bài viết</h5>
                        <p class="card-text">Thêm, sửa, xóa bài viết trên hệ thống.</p>
                        <a href="#" class="btn btn-primary">Truy cập</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
