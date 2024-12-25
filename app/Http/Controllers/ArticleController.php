<?php

// app/Http/Controllers/ArticleController.php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // Người đọc: Hiển thị danh sách bài viết
    public function index(Request $request)
    {
        $articles = Article::with('category', 'author')->paginate(10); // Lấy bài viết kèm theo thông tin category và author
        return view('reader.index', compact('articles'));
    }

    // Người đọc: Tìm kiếm bài viết
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $articles = Article::where('title', 'like', "%$keyword%")
                            ->orWhere('content', 'like', "%$keyword%")
                            ->with('category', 'author')
                            ->paginate(10);
        return view('reader.index', compact('articles'));
    }

    // Người đọc: Xem chi tiết bài viết
    public function show($id)
    {
        $article = Article::with('category', 'author', 'comments')->findOrFail($id);
        return view('reader.show', compact('article'));
    }

    // Tác giả: Thêm bài viết
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $article = Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'author_id' => auth()->id(), // Lấy ID của tác giả từ session
            'image' => $request->image // Xử lý ảnh nếu có
        ]);

        if ($request->tags) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('author.index')->with('success', 'Bài viết đã được tạo!');
    }

    // Tác giả: Sửa bài viết
    public function edit($id)
    {
        $article = Article::with('tags')->findOrFail($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('author.edit', compact('article', 'categories', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id'
        ]);

        $article = Article::findOrFail($id);
        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image' => $request->image // Xử lý ảnh nếu có
        ]);

        if ($request->tags) {
            $article->tags()->sync($request->tags);
        }

        return redirect()->route('author.index')->with('success', 'Bài viết đã được cập nhật!');
    }

    // Tác giả: Xóa bài viết
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('author.index')->with('success', 'Bài viết đã bị xóa!');
    }

    // Quản trị viên: Xóa bài viết
    public function adminDestroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();
        return redirect()->route('admin.index')->with('success', 'Bài viết đã bị xóa!');
    }
    // Admin Controller Method
public function adminIndex() {
    $articles = Article::all(); // Admin có thể xem tất cả bài viết
    return view('admin.index', compact('articles'));
}

// Author Controller Method
public function authorIndex() {
    $articles = Article::where('author_id', auth()->id())->get(); // Tác giả chỉ xem bài viết của mình
    return view('author.index', compact('articles'));
}

}
