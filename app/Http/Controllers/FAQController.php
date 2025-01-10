<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Http\Resources\FAQResource;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    /**
     * Display a listing of the FAQ.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Mengambil semua FAQ dari database
        $faqs = FAQ::all();
        return FaqResource::collection($faqs);
    }

    /**
     * Display the specified FAQ.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Mencari FAQ berdasarkan ID
        $faq = FAQ::findOrFail($id);

        // Mengembalikan resource FAQ sebagai JSON tanpa pesan "FAQ fetched successfully"
        return response()->json([
            'data' => new FAQResource($faq)
        ]);
    }

    /**
     * Store a newly created FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validasi data request
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        // Membuat FAQ baru
        $faq = FAQ::create([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        // Mengembalikan response dengan pesan sukses dan data FAQ yang baru dibuat
        return response()->json([
            'message' => 'FAQ created successfully!',
            'data' => new FAQResource($faq)
        ], 201);  // HTTP status 201 Created
    }

    /**
     * Update the specified FAQ in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi data request
        $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
        ]);

        // Mencari FAQ berdasarkan ID
        $faq = FAQ::findOrFail($id);

        // Update FAQ dengan data baru
        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
        ]);

        // Mengembalikan response dengan pesan sukses dan data FAQ yang telah diperbarui
        return response()->json([
            'message' => 'FAQ updated successfully!',
            'data' => new FAQResource($faq)
        ]);
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Mencari FAQ berdasarkan ID dan menghapusnya
        $faq = FAQ::findOrFail($id);
        $faq->delete();

        // Mengembalikan response dengan pesan sukses
        return response()->json([
            'message' => 'FAQ deleted successfully!'
        ]);
    }
}
