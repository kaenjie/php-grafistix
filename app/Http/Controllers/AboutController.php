<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Http\Resources\AboutResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abouts = About::all();
        return AboutResource::collection($abouts);
    }

    public function first()
    {
        $abouts = About::first();
        return response()->json([
            'judul' => $abouts->judul,
            'deskripsi' => $abouts->deskripsi,
            'image' => $abouts->image ? asset('storage/about/' . $abouts->image) : null,
            'created_at' => $abouts->created_at,
            'updated_at' => $abouts->updated_at,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = storage_path('app/public/about');
            $image->move($destinationPath, $imageName);
        }

        $about = About::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'image' => $imageName,
        ]);

        return response()->json([
            'message' => 'About created successfully!',
            'data' => new AboutResource($about)
        ], 201);  // HTTP status 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(About $about)
    {
        return new AboutResource($about);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, About $about)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destinationPath = storage_path('app/public/about');
            $image->move($destinationPath, $imageName);

            $about->image = $imageName;
        }

        $about->update([
            'judul' => $request->judul ?? $about->judul,
            'deskripsi' => $request->deskripsi ?? $about->deskripsi,
            'image' => $about->image,
        ]);

        return response()->json([
            'message' => 'About updated successfully!',
            'data' => new AboutResource($about)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(About $about)
    {
        if ($about->image && Storage::exists('public/about/' . $about->image)) {
            Storage::delete('public/about/' . $about->image);
        }

        $about->delete();

        return response()->json([
            'message' => 'About deleted successfully!'
        ]);
    }
}
