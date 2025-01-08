@extends('layouts.admin')

@section('content')
    <h1>Add Category</h1>
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div>
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>
        </div>
        <div>
            <label for="description">Description</label>
            <textarea name="description" id="description"></textarea>
        </div>
        <div>
            <label for="parent_id">Parent Category</label>
            <select name="parent_id" id="parent_id">
                <option value="">None</option>
                @foreach($categories as $parent)
                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Save</button>
    </form>
@endsection
