<?php

namespace App\Http\Controllers\Admin;

use App\Entity\Region;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = Region::where('parent_id')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $parent = null;

        if ($request->get('parent')) {
            $parent = Region::findOrFail($request->get('parent'));
        }

        return view('admin.regions.create', compact('parent'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            // unique:table, column, except, idColumn, anotherColumn, anotherColumnValue
            'name' => 'required|string|max:255|unique:regions,name,NULL,id,parent_id,'
                . ($request['parent'] ?: 'NULL'),
            'slug' => 'required|string|max:255|unique:regions,slug,NULL,id,parent_id,'
                . ($request['parent'] ?: 'NULL'),
            'parent' => 'nullable|integer|exists:regions,id',
        ]);

        $region = Region::create([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.regions.show', $region);
    }

    /**
     * Display the specified resource.
     */
    public function show(Region $region)
    {
        $regions = Region::where('parent_id', $region->id)
            ->orderBy('name')
            ->get();

        return view('admin.regions.show', compact('regions', 'region'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Region $region)
    {
        $this->validate($request, [
            // unique:table, column, except, idColumn, anotherColumn, anotherColumnValue
            'name' => 'required|string|max:255|unique:regions,name,' . $region->id
                . ',id,parent_id,' . $region->parent_id,
            'slug' => 'required|string|max:255|unique:regions,slug,' . $region->id
                . ',id,parent_id,' . $region->parent_id,
        ]);

        $region->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
        ]);

        return redirect()->route('admin.regions.show', $region);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region)
    {
        $region->delete();

        return redirect()->route('admin.regions.index');
    }
}
