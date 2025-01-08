@extends('layouts.admin')

@section('content')
    <h1>Edit Category</h1>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description">{{ old('description', $category->description) }}</textarea>
        </div>
        <div>
            <label for="parent_id">Parent Category</label>
            <select name="parent_id" id="parent_id">
                <option value="">None</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}" {{ $parent->id == $category->parent_id ? 'selected' : '' }}>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Update</button>
    </form>
@endsection
