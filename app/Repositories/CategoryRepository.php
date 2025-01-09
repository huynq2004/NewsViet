<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class CategoryRepository
{
    /**
     * Lấy tổng số bài viết trong một danh mục và các danh mục con.
     *
     * @param int $categoryId
     * @return int
     */
    public function getTotalArticles(int $categoryId): int
    {
        return DB::selectOne('SELECT dbo.fn_total_articles(?) AS total_articles', [$categoryId])->total_articles;
    }

    /**
     * Lấy danh sách danh mục con của một danh mục.
     *
     * @param int $categoryId
     * @return array
     */
    public function getSubcategories(int $categoryId): array
    {
        return DB::select('SELECT * FROM dbo.fn_get_subcategories(?)', [$categoryId]);
    }

    /**
     * Xóa danh mục và tất cả danh mục con.
     *
     * @param int $categoryId
     * @return void
     */
    public function deleteCategoryWithChildren(int $categoryId): void
    {
        DB::statement('EXEC sp_delete_category_with_children ?', [$categoryId]);
    }

    /**
     * Chuyển danh mục con từ một danh mục cha cũ sang danh mục cha mới.
     *
     * @param int $oldParentId
     * @param int $newParentId
     * @return void
     */
    public function moveSubcategories(int $oldParentId, int $newParentId): void
    {
        DB::statement('EXEC sp_move_subcategories ?, ?', [$oldParentId, $newParentId]);
    }

    /**
     * Lấy danh sách danh mục cùng tổng số bài viết (sử dụng view).
     *
     * @return array
     */
    public function getCategoriesWithTotalArticles(): array
    {
        return DB::select('SELECT * FROM vw_categories_with_total_articles');
    }

    /**
     * Lấy cây danh mục theo cấu trúc cha-con (sử dụng view).
     *
     * @return array
     */
    public function getCategoryTree(): array
    {
        return DB::select('SELECT * FROM vw_category_tree');
    }
}
