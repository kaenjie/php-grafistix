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
        $orders = Order::with('packages')->get();
        return response()->json($orders);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'paket' => 'required|string|max:255',
            'payment_method' => 'required|string|in:ovo,other', // Contoh validasi untuk metode pembayaran
            'has_paid' => 'required|boolean',
            'order_date' => 'required|date',
            'id_paket' => 'required|exists:packages,id', // Validasi untuk memastikan id_paket valid
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::create($request->all());
        return response()->json([
            'message' => 'Order successfully created!',
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
        $order = Order::with('package')->findOrFail($id); // Menampilkan order dengan relasi package
        return response()->json($order);
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
            'paket' => 'string|max:255',
            'payment_method' => 'string|in:ovo,other',
            'has_paid' => 'boolean',
            'order_date' => 'date',
            'id_paket' => 'exists:packages,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $order = Order::findOrFail($id);
        $order->update($request->all());

        return response()->json([
            'message' => 'Order successfully updated!',
            'data' => $order
        ]);
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
            'message' => 'Order successfully deleted!'
        ]);
    }
}
