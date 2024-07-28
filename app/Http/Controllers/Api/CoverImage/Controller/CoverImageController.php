<?php

namespace App\Http\Controllers\Api\CoverImage\Controller;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\CoverImage\Model\CoverImage;
use Illuminate\Http\Request;

class CoverImageController extends Controller
{
    public function getAllCoverImage(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = CoverImage::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $coverImage = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $coverImage->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $coverImage->currentPage(),
            'last_page' => $coverImage->lastPage(),
        ], 200);
    }

    public function postCoverImage(Request $request)
    {
        // Post request
        $request->validate([
            'link' => 'required|url',
        ]);

        $coverImage = CoverImage::create($request->all()); // Create a new CoverImage instance
        return response()->json([
            'message' => 'Successfully created ',
            'coverImage' => $coverImage // Return the created coverImage 
        ], 201);
    }

    public function getCoverImage(string $image_id)
    {
        // Find the specific resource
        $coverImage = CoverImage::find($image_id); // Use the correct model name
        if (!$coverImage) {
            return response()->json(['message' => 'cover image not found'], 404); // Handle not found cases
        }
        return $coverImage;
    }

    public function updateCoverImage(Request $request, string $image_id)
    {
        // Update the resource
        $coverImage = CoverImage::find($image_id); // Use the correct model name
        if (!$coverImage) {
            return response()->json(['message' => 'cover Image not found'], 404); // Handle not found cases
        }
        $coverImage->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'publisher' => $coverImage // Return the updated publisher data
        ], 200);
    }

    public function destroyCoverImage(string $image_id)
    {
        // Delete the resource
        $coverImage = CoverImage::find($image_id); // Use the correct model name
        if (!$coverImage) {
            return response()->json([['message' => 'Cover Image not found'], 404]); // Handle not found cases
        }
        $coverImage->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200); // Success
    }
}
