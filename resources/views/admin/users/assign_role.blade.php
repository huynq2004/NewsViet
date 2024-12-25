<!-- Blade view: users.assign_role -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Phân quyền người dùng</h1>
    <form action="{{ route('users.assignRole', $user->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="role">Chọn vai trò:</label>
            <select name="role_id" class="form-control">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật quyền</button>
    </form>
</div>
@endsection