<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->only(['index', 'show']);
    }
    public function index()
    {
        $orders = Order::with(['user'])->paginate(10);

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $product = Product::where('id', $item->product_id)->pluck('name');
                $item->product_name = $product['0'];
            }
        }

        return response()->json($orders);
    }

    public function show(Order $order)
    {
        $order->load(['orderItems', 'user', 'location']);
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $location = Location::where('user_id', Auth::id())->first();

        $validated = $request->validate([
            'total_price' => 'required|numeric',
            'date_of_delivery' => 'required',
            'quantity' => 'required',
            'order_items' => 'required',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'location_id' => $location->id,
            'total_price' => $validated['total_price'],
            'date_of_delivery' => $validated['date_of_delivery'],
        ]);

        foreach ($request->order_items as $order_item) {
            $order->orderItems()->create([
                'product_id' => $order_item['product_id'],
                'quantity' => $order_item['quantity'],
                'price' => $order_item['price'],
            ]);

            $product = Product::find($order_item['product_id']);
            $product->amount -= $order_item['quantity'];
            $product->save();
        }

        return response()->json($order, 201);
    }

    public function getOrderItems(Order $order)
    {
        $orderItems = $order->orderItems()->get();

        if (is_null($orderItems) || $orderItems->isEmpty()) {
            return response()->json(['message' => 'No order items found'], 404);
        }

        foreach ($orderItems as $item) {
            $product = Product::where('id', $item->product_id)->pluck('name');
            $item->product_name = $product['0'];
        }

        return response()->json($orderItems);
    }

    public function getUserOrders($userId)
    {
        $orders = Order::where('user_id', $userId)->get();
        if (is_null($orders) || $orders->isEmpty()) {
            return response()->json(['message' => 'No orders found for this user'], 404);
        }
        $orders->load('orderItems');

        foreach ($orders as $order) {
            foreach ($order->orderItems as $item) {
                $product = Product::where('id', $item->product_id)->pluck('name');
                $item->product_name = $product['0'];
            }
        }

        return response()->json($orders);
    }

    public function updateStatus(Request $request, Order $order)
    {

        $validated = $request->validate([
            'status' => 'required|in:Pending,Delivered,Cancelled,Accepted,Out of delivery',
        ]);

        $order->status = $validated['status'];
        $order->save();

        return response()->json($order);
    }
}
