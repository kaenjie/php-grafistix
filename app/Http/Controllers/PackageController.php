<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    /**
     * Display a listing of the packages.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $packages = Package::all(); // Retrieve all packages
        return response()->json(['data' => $packages]);
    }

    /**
     * Store a newly created package in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'is_active' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $package = Package::create($request->all());
        return response()->json(['message' => 'Package created successfully!', 'data' => $package]);
    }

    /**
     * Display the specified package.
     *
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function show(Package $package)
    {
        return response()->json(['data' => $package]);
    }

    /**
     * Update the specified package in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $package->update($request->all());
        return response()->json(['message' => 'Package updated successfully!', 'data' => $package]);
    }

    /**
     * Remove the specified package from storage.
     *
     * @param \App\Models\Package $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $package->delete();
        return response()->json(['message' => 'Package deleted successfully!']);
    }
}
