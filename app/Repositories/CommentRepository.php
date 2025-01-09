<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommentRepository
{
    // Trigger 1: Cập nhật `updated_at` khi bình luận được cập nhật
    public function updateCommentTimestamp($commentId, $newContent)
    {
        DB::statement("UPDATE comments SET content = ?, updated_at = GETDATE() WHERE id = ? AND content <> ?", [$newContent, $commentId, $newContent]);
    }

    // Trigger 2: Gán thời gian tạo cho bình luận nếu nội dung thay đổi
    public function setCommentCreatedAt($commentId, $content)
    {
        DB::statement("UPDATE comments SET created_at = GETDATE() WHERE id = ? AND LEN(content) > 0", [$commentId]);
    }

    // UDF 1: Lấy danh sách bình luận của bài viết chứa từ khóa
    public function getCommentsWithKeywordCursor($articleId, $keyword)
    {
        return DB::select("SELECT * FROM dbo.fn_get_comments_with_keyword_cursor(?, ?)", [$articleId, $keyword]);
    }

    // UDF 2: Lấy bình luận mới nhất của bài viết
    public function getLatestComment($articleId)
    {
        return DB::select("SELECT dbo.fn_get_latest_comment(?) AS latest_comment", [$articleId]);
    }

    // View 1: Xem bình luận kèm thông tin người dùng
    public function getCommentsWithUserInfo()
    {
        return DB::select("SELECT * FROM vw_comments_with_user_info");
    }

    // View 2: Xem số lượng bình luận theo bài viết
    public function getArticleCommentCounts()
    {
        return DB::select("SELECT * FROM vw_article_comment_counts");
    }

    // Proc 1: Lấy bình luận theo bài viết
    public function getCommentsByArticle($articleId)
    {
        return DB::select("EXEC sp_get_comments_by_article_with_cursor ?", [$articleId]);
    }

    // Proc 2: Cập nhật bình luận theo từ khóa
    public function updateCommentsByKeyword($keyword, $newContent)
    {
        DB::statement("EXEC sp_update_comments_by_keyword @keyword = ?, @new_content = ?", [$keyword, $newContent]);
    }
}