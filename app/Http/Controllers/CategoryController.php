<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         // Fetch all categories
         $categories = Category::all();

         return response()->json([
             'success' => true,
             'data' => $categories,
         ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
           // Create a new category
           $category = Category::create($request->validated());

           return response()->json([
               'success' => true,
               'message' => 'Category created successfully.',
               'data' => $category,
           ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
         // Return the specified category
         return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
         // Update the category with validated data
         $category->update($request->validated());

         return response()->json([
             'success' => true,
             'message' => 'Category updated successfully.',
             'data' => $category,
         ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
          // Delete the category
          $category->delete();

          return response()->json([
              'success' => true,
              'message' => 'Category deleted successfully.',
          ]);
    }
}
