<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Package;
use Illuminate\Http\Request;
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
        $orders = Order::with('package')->get(); // Eager load the package relationship
        return response()->json(['data' => $orders]);
    }

    /**
     * Store a newly created order in storage.
     *
     * @param \Illuminate\Http\Request $request
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
            'payment_method' => 'required|string|in:ovo,transfer,credit_card',
            'has_paid' => 'required|boolean',
            'order_date' => 'required|date',
            'id_paket' => 'required|exists:packages,id', // Ensure id_paket exists in packages table
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::create($request->all());
        return response()->json(['message' => 'Order created successfully!', 'data' => $order]);
    }

    /**
     * Display the specified order.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return response()->json(['data' => $order->load('package')]); // Load related package
    }

    /**
     * Update the specified order in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'string|max:255',
            'email' => 'email|max:255',
            'address' => 'string|max:255',
            'city' => 'string|max:255',
            'paket' => 'string|max:255',
            'payment_method' => 'string|in:ovo,transfer,credit_card',
            'has_paid' => 'boolean',
            'order_date' => 'date',
            'id_paket' => 'exists:packages,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->all());
        return response()->json(['message' => 'Order updated successfully!', 'data' => $order]);
    }

    /**
     * Remove the specified order from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully!']);
    }
}
