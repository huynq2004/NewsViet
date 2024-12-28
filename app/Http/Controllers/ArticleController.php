<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Repositories\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    // Khai báo repository
    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    // Hiển thị danh sách bài viết
    public function index()
    {
        $articles = Article::all();  // Lấy tất cả bài viết
        return view('admin.articles.index', compact('articles'));
    }

    // Xem chi tiết bài viết
    public function show($id)
    {
        // Lấy bài viết theo ID
        $article = Article::findOrFail($id);

        // Lấy các tag của bài viết từ function fn_get_article_tags
        $tags = $this->articleRepository->getArticleTags($id);  // Sử dụng phương thức repository

        // Trả về view chi tiết bài viết
        return view('reader.articles.show', compact('article', 'tags'));
    }

    // Tạo bài viết mới (cho tác giả)
    public function create()
    {
        // Lấy tất cả danh mục từ bảng categories
        $categories = \App\Models\Category::all();
    
        // Truyền biến categories vào view
        return view('author.articles.create', compact('categories'));
    }
    

    // Lưu bài viết mới (cho tác giả)
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Tạo bài viết mới và lưu vào cơ sở dữ liệu
        $article = new Article($request->all());
        $article->author_id = auth()->id();  // Gán tác giả là người đăng nhập
        $article->save();

        return redirect()->route('author.articles.index');
    }

    // Chỉnh sửa bài viết (cho tác giả)
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('author.articles.edit', compact('article'));
    }

    // Cập nhật bài viết (cho tác giả)
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        // Cập nhật bài viết
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return redirect()->route('author.articles.index');
    }

    // Xóa bài viết (Admin và Tác giả có thể xóa bài viết của mình)
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        // Kiểm tra nếu người dùng là admin hoặc là tác giả của bài viết
        if (auth()->user()->is_admin || auth()->id() == $article->author_id) {
            $article->delete();
            return redirect()->route('articles.index');
        }

        return redirect()->route('articles.index')->withErrors('Bạn không có quyền xóa bài viết này.');
    }

    // Lấy số lượng bài viết của tác giả (dùng function fn_count_articles_by_author)
    public function getArticleCountByAuthor($author_id)
    {
        // Gọi phương thức trong repository để lấy số lượng bài viết của tác giả
        $count = $this->articleRepository->countArticlesByAuthor($author_id);
        return response()->json($count);
    }

    // Hiển thị bài viết theo danh mục với chi tiết
    public function getArticlesByCategory($category_id)
    {
        // Gọi phương thức trong repository để lấy bài viết theo danh mục
        $articles = $this->articleRepository->getArticlesByCategory($category_id);
        return view('admin.articles.category', compact('articles'));
    }

    // Lấy danh sách bài viết cùng các thể loại và thẻ (sử dụng view vw_article_details)
    public function getArticlesWithDetails()
    {
        // Gọi phương thức trong repository để lấy danh sách bài viết với thông tin chi tiết
        $articles = $this->articleRepository->getArticleDetails();
        return view('admin.articles.details', compact('articles'));
    }

    // Lấy danh sách bài viết theo tác giả và thể loại (sử dụng view view_articles_with_details)
    public function getArticlesWithCategoryAndTags($author_id)
    {
        // Gọi phương thức trong repository để lấy bài viết theo tác giả và thể loại
        $articles = $this->articleRepository->getArticlesWithDetails();
        return view('admin.articles.author_category', compact('articles'));
    }
}
