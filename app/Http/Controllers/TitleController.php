<?php

namespace App\Http\Controllers;

use App\Models\Title;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TitleController extends Controller
{
    /**
     * Display a listing of the titles.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titles = Title::with('photos')->get(); // Eager load the related photos
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
            'poto_1' => 'nullable|string|max:255',
            'poto_2' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $title = Title::create($request->all());
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
        return response()->json(['data' => $title->load('photos')]); // Load related photos
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
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'poto_1' => 'nullable|string|max:255',
            'poto_2' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $title->update($request->all());
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
        $title->delete();
        return response()->json(['message' => 'Title deleted successfully!']);
    }
}
