<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::all();
        return response()->json([
            'message' => 'Orders retrieved successfully',
            'data' => $orders
        ], Response::HTTP_OK);
    }    

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
    public function store(Request $request)
    {
        // Debugging: Cek data yang diterima
        \Log::info($request->all());

        // Konversi nilai 'has_paid' menjadi boolean
        $request->merge([
            'has_paid' => filter_var($request->has_paid, FILTER_VALIDATE_BOOLEAN),
        ]);

        // Validasi input
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'payment_method' => 'string',
            'has_paid' => 'required|boolean',
            'package_id' => 'required',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Simpan data ke database
        $order = Order::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'payment_method' => $request->payment_method,
            'has_paid' => $request->has_paid,
            'package_id' => $request->package_id
        ]);

        return $request;
        return response()->json([
            'message' => 'Order created successfully!',
            'data' => $order
        ], Response::HTTP_CREATED);
    }

     

    /**
     * Display the specified order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::with('package')->findOrFail($id); // Memuat relasi package
    
        // Kembalikan resource dengan status dan pesan
        return response()->json(new \App\Http\Resources\OrderResource(true, 'Order retrieved successfully', $order), Response::HTTP_OK);
    }
    
    
    /**
     * Update the specified order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
             'full_name' => 'string|max:255',
             'email' => 'email|max:255',
             'address' => 'string|max:255',
             'city' => 'string|max:255',
             'payment_method' => 'string',
             'has_paid' => 'boolean',
             'order_date' => 'date',
             'id_paket' => 'exists:packages,id',
        ]);
            
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::findOrFail($id);

        \Log::info('Order Before Update:', $order->toArray());
        $order->update($request->all()); 
        \Log::info('Order After Update:', $order->toArray());
        return response()->json(new \App\Http\Resources\OrderResource(true, 'Order updated successfully!', $order), Response::HTTP_OK);
    }
    

    /**
     * Remove the specified order from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Order deleted successfully!',
        ], Response::HTTP_OK);
    }
    
}
