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
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title_id' => 'required|exists:titles,id', // Pastikan title_id ada di tabel titles
            'file_path' => 'required|file|mimes:jpeg,png,jpg|max:2048', // Validasi file upload
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan file yang diunggah
        $file = $request->file('file_path');
        $filePath = $file->store('uploads/photos', 'public'); // Simpan di storage/app/public/uploads/photos

        // Simpan data ke database
        $photo = Photo::create([
            'title_id' => $request->title_id,
            'file_path' => $filePath, // Path file yang tersimpan
        ]);

        // Kembalikan respons dengan URL file
        return response()->json([
            'message' => 'Photo created successfully!',
            'data' => $photo,
            'file_url' => asset('storage/' . $filePath), // URL akses file
        ]);
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
        dd($request);
        // $validator = Validator::make($request->all(), [
        //     'title_id' => 'exists:titles,id',
        //     'file_path' => 'file|mimes:jpeg,png,jpg|max:2048',
        // ]);


        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 422);
        // }

        // if ($request->hasFile('file_path')) {
        //     $file = $request->file('file_path');
        //     $filePath = $file->store('uploads/photos', 'public');
        //     $requestData = $request->all();
        //     $requestData['file_path'] = $filePath;
        // } else {
        //     $requestData = $request->except('file_path');
        // }

        // $photo->update($requestData);
        // return response()->json(['message' => 'Photo updated successfully!', 'data' => $photo]);
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
