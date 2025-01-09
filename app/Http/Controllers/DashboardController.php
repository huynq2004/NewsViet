<?php
namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use App\Models\Tag;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalArticles = Article::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalTags = Tag::count();

        return view('admin.dashboard', compact('totalArticles', 'totalUsers', 'totalCategories', 'totalTags'));
    }

    public function author()
    {
        $totalArticles = Article::count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalTags = Tag::count();

        return view('author.dashboard', compact('totalArticles', 'totalUsers', 'totalCategories', 'totalTags'));
    }

    public function reader()
    {
        $articles = Article::all();  // Lấy tất cả bài viết
        return view('reader.home', compact('articles'));
    }
}
