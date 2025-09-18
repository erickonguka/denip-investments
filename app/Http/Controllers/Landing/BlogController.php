<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::published()->with(['category', 'author']);
        
        if ($request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(9);
        $categories = BlogCategory::where('is_active', true)
            ->withCount('publishedBlogs')
            ->orderBy('name')
            ->get();

        $seoData = [
            'title' => 'Blog - Construction Industry Insights | Denip Investments Ltd',
            'description' => 'Stay updated with the latest construction industry news, project insights, and expert tips from Denip Investments Ltd.',
            'keywords' => 'construction blog, building tips, project management, Kenya construction, infrastructure development'
        ];

        return view('landing.blog.index', compact('blogs', 'categories', 'seoData'));
    }

    public function show(Blog $blog)
    {
        $blog->incrementViews();
        $blog->load(['category', 'author', 'approvedComments.replies']);
        
        // Get related blogs by category and tags
        $relatedBlogs = Blog::published()
            ->where(function($query) use ($blog) {
                $query->where('category_id', $blog->category_id);
                
                // Also include blogs with similar keywords
                if ($blog->meta_keywords && count($blog->meta_keywords) > 0) {
                    foreach ($blog->meta_keywords as $keyword) {
                        $query->orWhereJsonContains('meta_keywords', $keyword);
                    }
                }
            })
            ->where('id', '!=', $blog->id)
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$blog->category_id])
            ->limit(3)
            ->get();

        $seoData = [
            'title' => $blog->title . ' | Denip Investments Ltd Blog',
            'description' => $blog->meta_description ?: $blog->excerpt,
            'keywords' => $blog->meta_keywords ? implode(', ', $blog->meta_keywords) : 'construction, building, Kenya'
        ];

        return view('landing.blog.show', compact('blog', 'relatedBlogs', 'seoData'));
    }

    public function storeComment(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:blog_comments,id'
        ]);

        $validated['blog_id'] = $blog->id;
        $validated['status'] = 'pending';

        BlogComment::create($validated);

        return response()->json([
            'success' => true, 
            'message' => 'Comment submitted successfully and is pending approval.'
        ]);
    }
}