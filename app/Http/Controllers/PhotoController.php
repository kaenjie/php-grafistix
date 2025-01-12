<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PhotoResource;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Display a listing of the photos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photos = Photo::all();
        return PhotoResource::collection($photos);
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
            'title_id' => 'required|exists:titles,id',
            'file_path' => 'required|file|mimes:jpeg,png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan file di direktori public/uploads/photos
        $file = $request->file('file_path');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('/storage/uploads/photos');
        $file->move($destinationPath, $fileName);

        // Simpan data ke database
        $photo = Photo::create([
            'title_id' => $request->title_id,
            'file_path' => 'uploads/photos/' . $fileName, // Simpan path relatif
        ]);

        // Kembalikan respons
        return response()->json([
            'message' => 'Photo created successfully!',
            'data' => $photo,
            'file_url' => 'uploads/photos/' . $fileName, // Gunakan path relatif
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
            'title_id' => 'sometimes|exists:titles,id',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg|max:10240',
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
            // Hapus file lama jika ada file baru
            if ($photo->file_path && file_exists(public_path($photo->file_path))) {
                unlink(public_path($photo->file_path));
            }
    
            // Simpan file baru
            $file = $request->file('file_path');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('/storage/uploads/photos');
            $file->move($destinationPath, $fileName);
    
            // Update path file
            $photo->file_path = 'uploads/photos/' . $fileName;
        }
    
        // Simpan perubahan ke database
        $photo->save();
    
        // Kembalikan respons dengan data foto yang sudah diperbarui
        return response()->json([
            'message' => 'Photo updated successfully!',
            'data' => $photo,
            'file_url' => asset($photo->file_path), // URL akses file
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
