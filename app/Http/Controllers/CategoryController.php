<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $query = Category::query();
        
        // Handle search
        if (request()->has('search')) {
            $search = request()->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $categories = $query->withCount('tasks')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'color' => 'nullable|max:7',
        ]);

        $request->user()->categories()->create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'color' => 'nullable|max:7',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully!');
    }
} 