<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // Hiển thị danh sách bình luận của bài viết (có form thêm bình luận)
    public function index($articleId)
{
    // Ép kiểu $articleId thành kiểu int hoặc bigint
    $articleId = (int) $articleId;

    // Lấy danh sách bình luận của bài viết
    $comments = Comment::where('article_id', $articleId)
                       ->orderBy('created_at', 'desc')
                       ->get();

    // Trả về view hiển thị danh sách bình luận
    return view('reader.comments.index', compact('comments', 'articleId'));
}

public function edit($id)
{
    // Tìm bình luận theo ID hoặc trả về lỗi nếu không tìm thấy
    $comment = Comment::findOrFail($id);

    // Kiểm tra xem người dùng có quyền chỉnh sửa bình luận này không
    if ($comment->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bình luận này.');
    }

    // Trả về view để chỉnh sửa bình luận
    return view('reader.comments.edit', compact('comment'));
}


    // Thêm bình luận mới
    public function store(Request $request, $articleId)
{
    // Ép kiểu $articleId thành kiểu int
    $articleId = (int) $articleId;

    // Xác thực dữ liệu đầu vào
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Tạo bình luận mới
    $comment = new Comment();
    $comment->content = $request->input('content');
    $comment->article_id = $articleId;
    $comment->user_id = auth()->user()->id; // Giả sử người dùng đã đăng nhập
    $comment->save();

    return redirect()->route('comments.index', ['articleId' => $articleId])
                     ->with('success', 'Bình luận đã được thêm.');
}


public function show($id)
{
    $comment = Comment::findOrFail($id);
    return view('reader.comments.show', compact('comment'));
}

public function update(Request $request, $id)
{
    // Xác thực dữ liệu đầu vào
    $request->validate([
        'content' => 'required|string|max:1000',
    ]);

    // Tìm bình luận theo ID hoặc trả về lỗi nếu không tìm thấy
    $comment = Comment::findOrFail($id);

    // Kiểm tra quyền sửa bình luận
    if ($comment->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền chỉnh sửa bình luận này.');
    }

    // Cập nhật nội dung bình luận
    $comment->content = $request->input('content');
    $comment->save();

    // Redirect về trang danh sách bình luận của bài viết
    return redirect()->route('comments.index', ['articleId' => $comment->article_id])
                     ->with('success', 'Bình luận đã được cập nhật.');
}


public function destroy($id)
{
    $comment = Comment::findOrFail($id);
    if ($comment->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này.');
    }

    $articleId = $comment->article_id;
    $comment->delete();

    return redirect()->route('comments.index', ['articleId' => $articleId])
                     ->with('success', 'Bình luận đã bị xóa.');
}


public function report($id)
{
    $comment = Comment::findOrFail($id);
    $comment->is_reported = true; // Cập nhật trạng thái báo cáo
    $comment->save();

    return redirect()->back()->with('success', 'Bình luận đã được báo cáo.');
}


}