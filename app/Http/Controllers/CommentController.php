<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepository;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    
    public function _construct(CommentRepository $CommentRepository)
    {
        $this ->CommentRepository = $CommentRepository;
    }

    public function countChild(string $id)
    {
        $childnum = $this->CommentRepository->countChildComment(id:$id);
        return view( 'comments.index', compact('comment'));
    }
    /**
     * Hiển thị danh sách bình luận.
     */
    public function index()
    {
        $comments = Comment::with(['user', 'article'])->get(); // Lấy bình luận kèm thông tin người dùng và bài viết
        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Hiển thị form thêm bình luận.
     */
    public function create()
    {
        
        return view('admin.comments.create');
    }

    /**
     * Lưu bình luận mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'article_id' => 'required|exists:articles,id',
            'user_id' => 'required|exists:users,id',
        ]);

        Comment::create($request->all());
        return redirect()->route('admin.comments.index')->with('success', 'Thêm bình luận thành công.');
    }

    /**
     * Hiển thị form sửa bình luận.
     */
    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }
    

    /**
     * Cập nhật thông tin bình luận.
     */
    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($request->only('content'));
        return redirect()->route('admin.comments.index')->with('success', 'Cập nhật bình luận thành công.');
    }

    /**
     * Xóa bình luận.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return redirect()->route('admin.comments.index')->with('success', 'Xóa bình luận thành công.');
    }

    /**
     * Hiển thị danh sách bình luận chưa duyệt.
     */
    public function pending()
    {
        $comments = Comment::where('status', 'pending')->with(['user', 'article'])->get();
        return view('admin.comments.pending', compact('comments'));
    }

    /**
     * Hiển thị danh sách bình luận bị báo cáo.
     */
    public function reported()
    {
        $comments = Comment::where('status', 'reported')->with(['user', 'article'])->get();
        return view('admin.comments.reported', compact('comments'));
    }
}
