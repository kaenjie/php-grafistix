<?php

namespace App\Http\Controllers;

use App\Models\PackageCategory;
use Illuminate\Http\Request;
use App\Http\Resources\PackageCategoryResource;

class PackageCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $packageCategory = PackageCategory::all();
        return PackageCategoryResource::collection($packageCategory);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data request
        $request->validate([
            'name' => 'required|string',
            'price' => 'required',
        ]);

        $packageCategory = PackageCategory::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'PackageCategory created successfully!',
            'data' => new PackageCategoryResource($packageCategory)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PackageCategory $packageCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PackageCategory $packageCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required',
        ]);

        $packageCategory = PackageCategory::findOrFail($id);

        $packageCategory->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'PackageCategory updated successfully!',
            'data' => new PackageCategoryResource($packageCategory)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $packageCategory = PackageCategory::findOrFail($id);
        $packageCategory->delete();

        return response()->json([
            'message' => 'FAQ deleted successfully!'
        ]);
    }
}
