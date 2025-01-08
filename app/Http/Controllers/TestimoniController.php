<?php

namespace App\Http\Controllers;

use App\Models\Testimoni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class TestimoniController extends Controller
{
    /**
     * Display a listing of the testimonis.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $testimonis = Testimoni::all();
        return response()->json(['data' => $testimonis]);
    }

    /**
     * Store a newly created testimoni in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'feedback' => 'required|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Max 5 MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imageName = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->storeAs('public/testimoni', $imageName);
        }

        $testimoni = Testimoni::create([
            'name' => $request->name,
            'company' => $request->company,
            'role' => $request->role,
            'feedback' => $request->feedback,
            'image' => $imageName,
        ]);

        return response()->json(['message' => 'Testimoni created successfully!', 'data' => $testimoni]);
    }

    /**
     * Display the specified testimoni.
     *
     * @param \App\Models\Testimoni $testimoni
     * @return \Illuminate\Http\Response
     */
    public function show(Testimoni $testimoni)
    {
        return response()->json(['data' => $testimoni]);
    }

    /**
     * Update the specified testimoni in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Testimoni $testimoni
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Testimoni $testimoni)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'role' => 'nullable|string|max:255',
            'feedback' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imageName = $testimoni->image;

        if ($request->hasFile('image')) {
            if ($testimoni->image && Storage::exists('public/testimoni/' . $testimoni->image)) {
                Storage::delete('public/testimoni/' . $testimoni->image);
            }
            $image = $request->file('image');
            $imageName = time() . '_image.' . $image->getClientOriginalExtension();
            $image->storeAs('public/testimoni', $imageName);
        }

        $testimoni->update([
            'name' => $request->name ?? $testimoni->name,
            'company' => $request->company ?? $testimoni->company,
            'role' => $request->role ?? $testimoni->role,
            'feedback' => $request->feedback ?? $testimoni->feedback,
            'image' => $imageName,
        ]);

        return response()->json(['message' => 'Testimoni updated successfully!', 'data' => $testimoni]);
    }

    /**
     * Remove the specified testimoni from storage.
     *
     * @param \App\Models\Testimoni $testimoni
     * @return \Illuminate\Http\Response
     */
    public function destroy(Testimoni $testimoni)
    {
        if ($testimoni->image && Storage::exists('public/testimoni/' . $testimoni->image)) {
            Storage::delete('public/testimoni/' . $testimoni->image);
        }

        $testimoni->delete();

        return response()->json(['message' => 'Testimoni deleted successfully!']);
    }
}
