<?php
namespace App\Repositories;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryRepository
{
    public function countChildCategory($id)
    {
        return DB::select('EXEC countChildCategory');
    }

}