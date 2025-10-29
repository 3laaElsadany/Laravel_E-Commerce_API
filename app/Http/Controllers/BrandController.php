<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::paginate(10);
        return response()->json($brands);
    }

    public function show(Brand $brand)
    {

        return response()->json($brand);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:brands,name',
            ]);

            $brand = Brand::create([
                'name' => $request->name,
            ]);

            return response()->json($brand, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Brand $brand)
    {

        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            ]);

            $brand->name = $request->name;
            $brand->save();

            return response()->json($brand);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function destroy(Brand $brand)
    {

        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully']);
    }
}
