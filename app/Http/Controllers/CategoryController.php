<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    /**
     * Hiển thị danh sách danh mục với tổng số bài viết.
     */
    public function index()
    {
        $categories = $this->categoryRepo->getCategoriesWithTotalArticles();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị giao diện tạo danh mục mới.
     */
    public function create()
    {
        $categories = $this->categoryRepo->getCategoryTree();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Thêm mới danh mục.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Sử dụng Eloquent để tạo danh mục mới
        $category = new Category($request->all());
        return redirect()->route('admin.categories.index')->with('success', 'Category added successfully');
    }

    /**
     * Xóa danh mục và các danh mục con.
     */
    public function destroy($id)
    {
        $this->categoryRepo->deleteCategoryWithChildren($id);
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }

    /**
     * Hiển thị cây danh mục theo cấu trúc cha-con.
     */
    public function showTree()
    {
        $categoryTree = $this->categoryRepo->getCategoryTree();
        return view('admin.categories.tree', compact('categoryTree'));
    }

    /**
     * Lấy danh sách danh mục con của một danh mục.
     */
    public function getSubcategories($id)
    {
        $subcategories = $this->categoryRepo->getSubcategories($id);
        return response()->json($subcategories);
    }

    /**
     * Chuyển danh mục con sang danh mục cha mới.
     */
    public function moveSubcategories(Request $request)
    {
        $request->validate([
            'old_parent_id' => 'required|exists:categories,id',
            'new_parent_id' => 'required|exists:categories,id',
        ]);

        $this->categoryRepo->moveSubcategories($request->old_parent_id, $request->new_parent_id);
        return redirect()->route('admin.categories.index')->with('success', 'Subcategories moved successfully');
    }
}
