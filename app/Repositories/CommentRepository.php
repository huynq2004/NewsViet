<?php

namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class CommentRepository 
{
    
    public function countChildComment($id)
    {
        return DB::select('EXEC countChildComment');
    }

    
}
