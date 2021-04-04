<?php

namespace App\Http\Controllers\Admin\Adverts;

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Adverts\AttributeRequest;

class AttributeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Category $category)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.create', compact('category', 'types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AttributeRequest $request, Category $category)
    {
        $attribute = $category->attributes()->create([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('/[\r\n]+/', $request['variants'])),
            'sort' => $request['sort'],
        ]);

        return redirect()->route('admin.adverts.categories.attributes.show', [$category, $attribute]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, Attribute $attribute)
    {
        return view('admin.adverts.categories.attributes.show', compact('category', 'attribute'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, Attribute $attribute)
    {
        $types = Attribute::typesList();

        return view('admin.adverts.categories.attributes.edit', compact('category', 'attribute', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AttributeRequest $request, Category $category, Attribute $attribute)
    {
        $category->attributes()->findOrFail($attribute->id)->update([
            'name' => $request['name'],
            'type' => $request['type'],
            'required' => (bool)$request['required'],
            'variants' => array_map('trim', preg_split('/[\r\n]+/', $request['variants'])),
            'sort' => $request['sort'],
        ]);

        return redirect()->route('admin.adverts.categories.show', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, Attribute $attribute)
    {
        $category->attributes()->findOrFail($attribute->id)->delete();

        return redirect()->route('admin.adverts.categories.show', $category);
    }
}
