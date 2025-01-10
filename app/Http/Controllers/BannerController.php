<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Http\Resources\BannerResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the banners.
     */
    public function index()
    {
        $banners = Banner::all();
        return BannerResource::collection($banners);
    }

    public function first()
    {
        $banners = Banner::first();
        return response()->json([
            'file' => $banners->file,
            'image_url' => $banners->file ? asset('storage/banners/' . $banners->file) : null,
            'created_at' => $banners->created_at,
            'updated_at' => $banners->updated_at,
        ]);
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        // Validasi file gambar dengan key 'file'
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Menyimpan file gambar dengan key 'file'
        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = storage_path('app/public/banners');
        $file->move($destinationPath, $fileName);

        // Menyimpan data banner di database
        $banner = Banner::create([
            'file' => $fileName,
        ]);

        return response()->json([
            'message' => 'Banner created successfully!',
            'data' => new BannerResource($banner),
        ], 201);  // HTTP status 201 Created
    }

    /**
     * Display the specified banner.
     */
    public function show($id)
    {
        $banner = Banner::findOrFail($id);
        return new BannerResource($banner);
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, $id)
    {
        // Validasi file gambar dengan key 'file'
        $request->validate([
            'file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Mencari banner berdasarkan ID
        $banner = Banner::findOrFail($id);

        // Menghapus file lama jika ada dan menggantinya dengan file baru
        if ($request->hasFile('file')) {
            if ($banner->file && Storage::exists('public/banners/' . $banner->file)) {
                Storage::delete('public/banners/' . $banner->file);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = storage_path('app/public/banners');
            $file->move($destinationPath, $fileName);
            $banner->file = $fileName;
        }

        // Update data banner
        $banner->save();

        return response()->json([
            'message' => 'Banner updated successfully!',
            'data' => new BannerResource($banner),
        ]);
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);

        // Menghapus file gambar dari storage
        if ($banner->file && Storage::exists('public/banners/' . $banner->file)) {
            Storage::delete('public/banners/' . $banner->file);
        }

        $banner->delete();

        return response()->json([
            'message' => 'Banner deleted successfully!'
        ]);
    }
}
