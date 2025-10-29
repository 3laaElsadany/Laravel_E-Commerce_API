<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware(['admin'])->except(['index', 'show']);
    }

    public function index()
    {
        $products = Product::paginate(10);

        if (!$products->count()) {
            return response()->json(['message' => 'No products found'], 404);
        }

        return response()->json($products);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'required|exists:brands,id',
                'is_trending' => 'sometimes|boolean',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp',
                'amount' => 'required|integer',
                'discount' => 'sometimes|numeric',
                'price' => 'required|numeric',
            ]);

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);

            $product = Product::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'brand_id' => $request->brand_id,
                'is_trending' => $request->is_trending ?? false,
                'image' => $imageName,
                'amount' => $request->amount,
                'discount' => $request->discount,
                'price' => $request->price,
            ]);

            return response()->json($product, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, Product $product)
    {

        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'category_id' => 'sometimes|exists:categories,id',
                'brand_id' => 'sometimes|exists:brands,id',
                'is_trending' => 'sometimes|boolean',
                'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp',
                'amount' => 'sometimes|integer',
                'discount' => 'sometimes|numeric',
                'price' => 'sometimes|numeric',
            ]);

            $product->name = $request->name ?? $product->name;
            $product->category_id = $request->category_id ?? $product->category_id;
            $product->brand_id = $request->brand_id ?? $product->brand_id;
            $product->is_trending = $request->is_trending ?? $product->is_trending;
            $product->amount = $request->amount ?? $product->amount;
            $product->discount = $request->discount ?? $product->discount;
            $product->price = $request->price ?? $product->price;

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path('uploads/' . $product->image))) {
                    unlink(public_path('uploads/' . $product->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);

                $product->image = $imageName;
            }
            $product->save();
            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product)
    {

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
