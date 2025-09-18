<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('blogs')->latest()->paginate(15);
        return view('blog-categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|size:7',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        BlogCategory::create($validated);

        return response()->json(['success' => true, 'message' => 'Category created successfully']);
    }

    public function edit(BlogCategory $blogCategory)
    {
        return response()->json(['success' => true, 'data' => $blogCategory]);
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|size:7',
            'is_active' => 'boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->has('is_active');

        $blogCategory->update($validated);

        return response()->json(['success' => true, 'message' => 'Category updated successfully']);
    }

    public function destroy(BlogCategory $blogCategory)
    {
        if ($blogCategory->blogs()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete category with existing blog posts']);
        }

        $blogCategory->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
    }
}