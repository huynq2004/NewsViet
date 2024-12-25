<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Tag;

class TagController extends Controller
{
    //Hiển thị danh sách thẻ (index)
    public function index()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    //Thêm thẻ mới (store)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::statement('EXEC AddTag ?, ?', [$request->name, $request->description]);

        return response()->json(['message' => 'Tag created successfully.']);
    }

    //Xem thông tin thẻ cụ thể (show)
    public function show($id)
    {
        $tag = Tag::find($id);

        if (!$tag) {
            return response()->json(['error' => 'Tag not found.'], 404);
        }

        return response()->json($tag);
    }

    //Cập nhật thông tin thẻ (update)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::statement('EXEC UpdateTag ?, ?, ?', [$id, $request->name, $request->description]);

        return response()->json(['message' => 'Tag updated successfully.']);
    }

    //Xóa thẻ (destroy)
    public function destroy($id)
    {
        DB::statement('EXEC DeleteTag ?', [$id]);

        return response()->json(['message' => 'Tag deleted successfully.']);
    }
}
