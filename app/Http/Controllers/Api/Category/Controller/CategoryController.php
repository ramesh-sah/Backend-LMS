<?php

namespace App\Http\Controllers\Api\Category\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Category\Model\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getAllCategory(Request $request)
    {
        return Category::All();
    }
    public function postCategory(Request $request)
    {
        // Post request
        $request->validate([
            'category_name' => 'required|string',
        ]);

        $category = Category::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created',
            'publisher' => $category // Return the created publisher data
        ], 201]);
    }

    public function getCategory(string $category_id)
    {
        // Find the specific resource
        $category = Category::find($category_id); // Use the correct model name
        if (!$category) {
            return response()->json([['message' => 'Category not found'], 404]); // Handle not found cases
        }
        return  response()->json([$category]);
    }

    public function updateCategory(Request $request, string $category_id)
    {
        // Update the resource
        $category = Category::find($category_id); // Use the correct model name
        if (!$category) {
            return response()->json([['message' => 'Category not found'], 404]); // Handle not found cases
        }
        $category->updateCategory($request->all());
        return response()->json([[
            'message' => 'Successfully updated',
            'publisher' => $category // Return the updated publisher data
        ], 200]);
    }

    public function destroyCategory(string $category_id)
    {
        // Delete the resource
        $category = Category::find($category_id); // Use the correct model name
        if (!$category) {
            return response()->json([['message' => 'Category  not found'], 404]); // Handle not found cases
        }
        $category->delete();
        return response()->json([[
            'message' => 'Successfully deleted'
        ], 200]);
    }
}
