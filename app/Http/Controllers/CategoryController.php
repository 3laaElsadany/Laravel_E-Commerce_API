<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);
        return response()->json($categories);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpg,jpeg,png,webp',
            ]);

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('uploads'), $imageName);

            $category = Category::create([
                'name' => $request->name,
                'image' => $imageName,
            ]);

            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, Category $category)
    {

        try {
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'image' => 'sometimes|image|mimes:jpg,jpeg,png,webp',
            ]);

            if ($request->has('name')) {
                $category->name = $request->input('name');
            }

            if ($request->hasFile('image')) {
                if ($category->image && file_exists(public_path('uploads/' . $category->image))) {
                    unlink(public_path('uploads/' . $category->image));
                }

                $imageName = time() . '.' . $request->image->extension();
                $request->image->move(public_path('uploads'), $imageName);
                $category->image = $imageName;
            }

            $category->save();

            return response()->json($category);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }


    public function destroy(Category $category)
    {

        if ($category->image && file_exists(public_path('uploads/' . $category->image))) {
            unlink(public_path('uploads/' . $category->image));
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
