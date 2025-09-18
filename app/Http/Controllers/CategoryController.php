<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(15);
        return view('categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        $projects = $category->projects()->with(['client', 'creator'])->latest()->paginate(15);
        return view('categories.show', compact('category', 'projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        Category::create($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category created successfully']);
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category updated successfully']);
        }

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        // Delete associated projects first
        $category->projects()->delete();
        
        // Then delete the category
        $category->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category and associated projects deleted successfully']);
        }

        return redirect()->route('categories.index')->with('success', 'Category and associated projects deleted successfully.');
    }

    public function toggle(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Category status updated']);
        }
        
        return redirect()->route('categories.index')->with('success', 'Category status updated.');
    }
}