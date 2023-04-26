<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::query();

        if (request()->has('search')) {
            $search = request('search');
            $categories->where('name', 'like', "%$search%");
        }

        $categories = $categories->paginate(10);

        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = new Category();
        $category->fill($request->validated());
        $category->save();
        return response()->json(['status'=> 'SUCCESS', 'data' => $category]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if (!$category) 
            return response()->json(['status'=> 'BAD_REQUEST', 'message' => 'Data tidak ditemukan.'], 400);
        return response()->json(['status'=> 'SUCCESS', 'data' => Category::find($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if (!$category) 
            return response()->json(['status'=> 'BAD_REQUEST', 'message' => 'Data tidak ditemukan.'], 400);

        $category->fill($request->validated());
        $category->save();
        return response()->json(['status'=> 'SUCCESS', 'data' => $category]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if (!$category) 
            return response()->json(['status'=> 'BAD_REQUEST', 'message' => 'Data tidak ditemukan.'], 400);
        $category->delete();
        return response()->json(['status'=> 'SUCCESS', 'data' => ['id' => $id]]);
    }
}
