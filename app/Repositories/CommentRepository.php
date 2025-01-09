<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CommentRepository
{
    /**
     * Đếm số lượng bình luận con của một bình luận cụ thể.
     *
     * @param int $id ID của bình luận cha
     * @return int Số lượng bình luận con
     */
    public function countChildComment($id)
    {
        return DB::select('EXEC countChildComment @comment_id = ?', [$id]);
    }

    /**
     * Lấy danh sách bình luận kèm thông tin người dùng.
     *
     * @return array Danh sách bình luận
     */
    public function getCommentsWithUserInfo()
    {
        return DB::select('SELECT * FROM vw_comments_with_user_info');
    }

    /**
     * Lấy số lượng bình luận theo từng bài viết.
     *
     * @return array Danh sách số lượng bình luận theo bài viết
     */
    public function getArticleCommentCounts()
    {
        return DB::select('SELECT * FROM vw_article_comment_counts');
    }

    /**
     * Lấy bình luận mới nhất của một bài viết.
     *
     * @param int $articleId ID của bài viết
     * @return string Nội dung bình luận mới nhất
     */
    public function getLatestCommentByArticle($articleId)
    {
        return DB::selectOne('SELECT dbo.fn_get_latest_comment(?) AS latest_comment', [$articleId])->latest_comment ?? null;
    }

    /**
     * Cập nhật nội dung bình luận theo từ khóa.
     *
     * @param string $keyword Từ khóa cần tìm
     * @param string $newContent Nội dung mới
     * @return int Số lượng bình luận được cập nhật
     */
    public function updateCommentsByKeyword($keyword, $newContent)
    {
        $result = DB::select('EXEC sp_update_comments_by_keyword @keyword = ?, @new_content = ?', [$keyword, $newContent]);
        return $result[0]->RowsAffected ?? 0;
    }

    /**
     * Lấy danh sách bình luận của một bài viết cụ thể có chứa từ khóa.
     *
     * @param int $articleId ID của bài viết
     * @param string $keyword Từ khóa cần tìm
     * @return array Danh sách bình luận
     */
    public function getCommentsWithKeyword($articleId, $keyword)
    {
        return DB::select('SELECT * FROM dbo.fn_get_comments_with_keyword_cursor(?, ?)', [$articleId, $keyword]);
    }
}
