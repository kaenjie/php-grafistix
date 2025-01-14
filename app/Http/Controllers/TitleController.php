<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TitleController extends Controller
{
    /**
     * Display a listing of the titles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titles = Title::with('photos')->get();
        return response()->json(['data' => $titles]);
    }

    /**
     * Store a newly created title in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'poto_1' => 'nullable|image|mimes:jpeg,png,jpg,gif', 
            'poto_2' => 'nullable|image|mimes:jpeg,png,jpg,gif', 
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // poto_1
        $file1Name = null;
        if ($request->hasFile('poto_1')) {
            $file1 = $request->file('poto_1');
            $file1Name = time() . '_poto_1.' . $file1->getClientOriginalExtension();
            $destinationPath = public_path('/storage/title');
            $file1->move($destinationPath, $file1Name);
        }

        // poto_2
        $file2Name = null;
        if ($request->hasFile('poto_2')) {
            $file2 = $request->file('poto_2');
            $file2Name = time() . '_poto_2.' . $file2->getClientOriginalExtension();
            $destinationPath = public_path('/storage/title');
            $file2->move($destinationPath, $file2Name);
        }

        // Tambah data
        $title = Title::create([
            'name' => $request->name,
            'poto_1' => $file1Name,
            'poto_2' => $file2Name,
            'deskripsi' => $request->deskripsi
        ]);

        return response()->json(['message' => 'Title created successfully!', 'data' => $title]);
    }

    /**
     * Display the specified title.
     *
     * @param \App\Models\Title $title
     * @return \Illuminate\Http\Response
     */
    public function show(Title $title)
    {
        $title->load('photos');
        return response()->json(['data' => $title]);
    }

    /**
     * Update the specified title in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Title $title
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Title $title)
    {
        \Log::info($request->all());

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'poto_1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'poto_2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'deskripsi' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $url_photo1 = $title -> poto_1;
        $file1Name = basename($url_photo1);
        // Handle poto_1 upload jika ada perubahan
        if ($request->hasFile('poto_1')) {
            $file1 = $request->file('poto_1');
            $file1Name = time() . '_poto_1.' . $file1->getClientOriginalExtension();
            $destinationPath = public_path('/storage/title');
            $file1->move($destinationPath, $file1Name);
            $title->poto_1 = $file1Name;  // Perbarui poto_1 dengan nama file baru
        }

        $url_photo2 = $title -> poto_2;
         $file2Name = basename($url_photo2);
        // Handle poto_2 upload jika ada perubahan
        if ($request->hasFile('poto_2')) {

            $file2 = $request->file('poto_2');
            $file2Name = time() . '_poto_2.' . $file2->getClientOriginalExtension();
            $destinationPath = public_path('/storage/title');
            $file2->move($destinationPath, $file2Name);
            $title->poto_2 = $file2Name;  // Perbarui poto_2 dengan nama file baru
        }

        // Simpan data yang diperbarui
        $title->update([
            'name' => $request->name,
            'poto_1' => $file1Name,
            'poto_2' => $file2Name,
            'deskripsi' => $request->deskripsi
        ]);
        // $title->save();

        return response()->json(['message' => 'Title updated successfully!', 'data' => $title]);
    }


    /**
     * Remove the specified title from storage.
     *
     * @param \App\Models\Title $title
     * @return \Illuminate\Http\Response
     */
    public function destroy(Title $title)
    {
        // Remove the files if they exist
        if ($title->poto_1 && Storage::exists('public/title/' . $title->poto_1)) {
            Storage::delete('public/title/' . $title->poto_1);
        }
        if ($title->poto_2 && Storage::exists('public/title/' . $title->poto_2)) {
            Storage::delete('public/title/' . $title->poto_2);
        }

        $title->delete();
        return response()->json(['message' => 'Title deleted successfully!']);
    }
}
