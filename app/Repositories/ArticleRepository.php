<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Article;

class ArticleRepository
{
    // Phương thức đếm số bài viết của tác giả
    public function countArticlesByAuthor($authorId)
    {
        $result = DB::select('SELECT dbo.fn_count_articles_by_author(?) AS article_count', [$authorId]);
        return $result[0]->article_count ?? 0;
    }

    // Phương thức lấy danh sách các bài viết theo danh mục
    public function getArticlesByCategory($categoryId)
    {
        $result = DB::select('EXEC sp_get_articles_by_category ?', [$categoryId]);
        return $result ?: [];
    }

    // Phương thức thêm một bài viết mới
    public function addArticle($title, $content, $image, $categoryId, $authorId)
    {
        return $this->executeStoredProcedure('sp_add_article', [
            $title,
            $content,
            $image,
            $categoryId,
            $authorId
        ]);
    }

    // Phương thức lấy các thẻ của bài viết
    public function getArticleTags($articleId)
    {
        $result = DB::select('SELECT dbo.fn_get_article_tags(?) AS tags', [$articleId]);
        return $result[0]->tags ?? [];
    }

    // Phương thức lấy danh sách bài viết với thông tin thẻ và thể loại
    public function getArticleDetails()
    {
        return DB::select('SELECT * FROM vw_article_details');
    }

    // Phương thức lấy danh sách bài viết với thể loại và tác giả
    public function getArticlesWithDetails()
    {
        return DB::select('SELECT * FROM view_articles_with_details');
    }

    // Phương thức lấy bài viết theo con trỏ
    public function fetchArticlesWithCursor()
    {
        return DB::select('EXEC FetchArticlesWithCursor');
    }

    // Phương thức đếm số từ trong bài viết
    public function countTotalWordsInArticles()
    {
        $result = DB::select('EXEC CountTotalWordsInArticles');
        return $result[0]->total_words ?? 0;
    }

    // Phương thức chung để thực thi stored procedure
    private function executeStoredProcedure($procedure, $params)
    {
        return DB::select("EXEC $procedure", $params);
    }
}
