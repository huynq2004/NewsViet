<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class TagRepository
{
    // Thực thi procedure để thêm hashtag mới vào hệ thống
    public function addNewHashtag($tagName)
    {
        DB::statement("EXEC AddNewHashtag @TagName = ?", [$tagName]);
    }

    // Thực thi procedure để cập nhật hashtag cho bài viết
    public function updateHashtagCursor($oldTagName, $newTagName)
    {
        DB::statement("EXEC UpdateHashtagCursor @OldTagName = ?, @NewTagName = ?", [$oldTagName, $newTagName]);
    }

    // Kiểm tra sự tồn tại của một hashtag trong hệ thống
    public function checkHashtagExists($hashtagName)
    {
        return DB::select("SELECT dbo.CheckHashtagExists(?) AS exists", [$hashtagName]);
    }

    // Lấy danh sách bài viết theo hashtag
    public function getArticlesByHashtag($hashtagName)
    {
        return DB::select("SELECT * FROM dbo.GetArticlesByHashtag(?)", [$hashtagName]);
    }

    // Đếm số lượng bài viết cho một hashtag cụ thể (có thể là một hàm riêng nếu cần)
    public function countArticlesByTag($tagId)
    {
        return DB::table('article_tag')->where('tag_id', $tagId)->count();
    }
}
