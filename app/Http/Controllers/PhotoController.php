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
        // Mengambil semua foto dengan relasi 'title'
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
        // Validasi input
        $validator = Validator::make($request->all(), [
            'title_id' => 'sometimes|exists:titles,id', // Jika ada title_id, pastikan ada di tabel titles
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg|max:2048', // Jika ada file baru, validasi file
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Perbarui 'title_id' jika ada dalam request
        if ($request->has('title_id')) {
            $photo->title_id = $request->title_id;
        }

        // Perbarui file jika ada dalam request
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $filePath = $file->store('uploads/photos', 'public');
            $photo->file_path = $filePath; // Menyimpan path file yang baru
        }

        // Simpan perubahan ke database
        $photo->save();

        // Kembalikan respons dengan data foto yang sudah diperbarui
        return response()->json([
            'message' => 'Photo updated successfully!',
            'data' => $photo,
            'file_url' => asset('storage/' . $photo->file_path), // URL akses file
        ]);
    }

    /**
     * Remove the specified photo from storage.
     *
     * @param \App\Models\Photo $photo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Photo $photo)
    {
        // Hapus file terkait jika ada
        if (file_exists(storage_path('app/public/' . $photo->file_path))) {
            unlink(storage_path('app/public/' . $photo->file_path)); // Menghapus file fisik
        }

        // Hapus entri foto dari database
        $photo->delete();

        return response()->json(['message' => 'Photo deleted successfully!']);
    }
}
