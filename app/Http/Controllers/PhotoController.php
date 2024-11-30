<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PhotoController extends Controller
{
    /**
     * Display a listing of the photos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::with('title')->get(); // Eager load the related Title
        return response()->json(['data' => $photos]);
    }

    /**
     * Store a newly created photo in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_id' => 'required|exists:titles,id', // Ensure title_id exists in titles table
            'file_path' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photo = Photo::create($request->all());
        return response()->json(['message' => 'Photo created successfully!', 'data' => $photo]);
    }

    /**
     * Display the specified photo.
     *
     * @param \App\Models\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function show(Photo $photo)
    {
        return response()->json(['data' => $photo->load('title')]); // Load related title
    }

    /**
     * Update the specified photo in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Photo $photo)
    {
        $validator = Validator::make($request->all(), [
            'title_id' => 'exists:titles,id',
            'file_path' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photo->update($request->all());
        return response()->json(['message' => 'Photo updated successfully!', 'data' => $photo]);
    }

    /**
     * Remove the specified photo from storage.
     *
     * @param \App\Models\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        $photo->delete();
        return response()->json(['message' => 'Photo deleted successfully!']);
    }
}
