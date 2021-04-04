<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\CategoryRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents = Category::defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.create', compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show', $category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $parentAttributes = $category->parentAttributes();
        $attributes = $category->attributes()->orderBy('sort')->get();

        return view('admin.adverts.categories.show', compact('category', 'attributes', 'parentAttributes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parents = Category::defaultOrder()->withDepth()->get();

        return view('admin.adverts.categories.edit', compact('parents', 'category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.adverts.categories.show', $category);
    }

    public function up(Category $category)
    {
        $category->up();

        return redirect()->route('admin.adverts.categories.index');
    }

    public function down(Category $category)
    {
        $category->down();

        return redirect()->route('admin.adverts.categories.index');
    }

    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }

        return redirect()->route('admin.adverts.categories.index');
    }

    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }

        return redirect()->route('admin.adverts.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.adverts.categories.index');
    }
}
