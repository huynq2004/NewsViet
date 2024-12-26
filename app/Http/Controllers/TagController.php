<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Article;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the tags.
     */
    public function index()
    {
        $tags = Tag::withCount('articles')->paginate(10);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created tag in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags',
        ]);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Hashtag mới đã được tạo thành công!');
    }

    /**
     * Display the specified tag and its associated articles.
     */
    public function show(string $id)
    {
        $tag = Tag::with('articles')->findOrFail($id);
        return view('admin.tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified tag.
     */
    public function edit(string $id)
    {
        $tag = Tag::findOrFail($id);
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update the specified tag in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $id,
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Hashtag đã được cập nhật thành công!');
    }

    /**
     * Remove the specified tag from storage.
     */
    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);

        // Xóa tag
        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Hashtag đã được xóa thành công!');
    }

    /**
     * Display the articles associated with a specific tag.
     */
    public function articlesByTag(string $id)
    {
        $tag = Tag::with('articles')->findOrFail($id);
        $articles = $tag->articles()->paginate(10);
        return view('admin.tags.articles', compact('tag', 'articles'));
    }
}
