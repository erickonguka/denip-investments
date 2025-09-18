<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with(['category', 'author'])
            ->latest()
            ->paginate(15);
        
        return view('blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = BlogCategory::where('is_active', true)->get();
        return view('blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'status' => 'required|in:draft,published'
        ]);

        $validated['author_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        if ($validated['meta_keywords']) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Blog::create($validated);

        return response()->json(['success' => true, 'message' => 'Blog post created successfully']);
    }

    public function show(Blog $blog)
    {
        $blog->load(['category', 'author', 'approvedComments.replies']);
        return view('blogs.show', compact('blog'));
    }

    public function edit(Blog $blog)
    {
        $categories = BlogCategory::where('is_active', true)->get();
        $blogData = $blog->load('category')->toArray();
        
        return response()->json([
            'success' => true,
            'data' => $blogData
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:blog_categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'meta_keywords' => 'nullable|string',
            'meta_description' => 'nullable|string|max:160',
            'status' => 'required|in:draft,published',

            'removed_media' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Handle featured image removal (from dropzone component)
        if ($request->input('removed_media')) {
            $removedIndices = json_decode($request->input('removed_media'), true) ?? [];
            if (!empty($removedIndices) && $blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
                $validated['featured_image'] = null;
            }
        } elseif ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        if ($validated['meta_keywords']) {
            $validated['meta_keywords'] = array_map('trim', explode(',', $validated['meta_keywords']));
        }

        if ($validated['status'] === 'published' && !$blog->published_at) {
            $validated['published_at'] = now();
        }

        // Remove the removal flags from validated data before updating
        unset($validated['removed_media']);

        $blog->update($validated);

        return response()->json(['success' => true, 'message' => 'Blog post updated successfully']);
    }

    public function destroy(Blog $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        
        $blog->delete();

        return response()->json(['success' => true, 'message' => 'Blog post deleted successfully']);
    }
}