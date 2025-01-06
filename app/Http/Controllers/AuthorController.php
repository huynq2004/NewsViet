<?php 

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    // Trang dashboard của tác giả
    public function dashboard()
    {
        // Lấy danh sách bài viết của tác giả hiện tại
        $articles = Article::where('author_id', auth()->id())->get();

        // Trả về view dashboard cho tác giả với danh sách bài viết
        return view('author.dashboard', compact('articles'));
    }
}
