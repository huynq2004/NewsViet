<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    // Trang dashboard của người đọc
    public function dashboard()
    {
        //dd("haha");
        // Lấy danh sách bài viết (có thể là tất cả bài viết hoặc lọc theo người đọc)
        $articles = Article::all(); // Hoặc nếu có điều kiện thì sử dụng: ->where('condition')

        // Trả về view dashboard cho người đọc với danh sách bài viết
        return view('reader.dashboard', compact('articles'));
    }
}
