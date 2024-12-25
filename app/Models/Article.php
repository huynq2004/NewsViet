<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'image',
        'category_id',
        'author_id',
    ];

    //Quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    //Quan hệ với User (Author)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    //Quan hệ với Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //Quan hệ nhiều-nhiều với Tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tag');
    }
}