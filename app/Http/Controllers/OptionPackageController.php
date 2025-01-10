<?php

namespace App\Http\Controllers;

use App\Models\OptionPackage;
use Illuminate\Http\Request;
use App\Http\Resources\OptionPackageResource;

class OptionPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $optionPackages = OptionPackage::with('category')->get();
        return OptionPackageResource::collection($optionPackages);
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
            'package_id' => 'required',
            'name' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $optionPackage = OptionPackage::create([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'OptionPackage created successfully!',
            'data' => new OptionPackageResource($optionPackage),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(OptionPackage $optionPackage)
    {
        return new OptionPackageResource($optionPackage);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OptionPackage $optionPackage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OptionPackage $optionPackage)
    {
        $request->validate([
            'package_id' => 'required',
            'name' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $optionPackage->update([
            'package_id' => $request->package_id,
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'OptionPackage updated successfully!',
            'data' => new OptionPackageResource($optionPackage),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OptionPackage $optionPackage)
    {
        $optionPackage->delete();

        return response()->json([
            'message' => 'OptionPackage deleted successfully!',
        ]);
    }
}
