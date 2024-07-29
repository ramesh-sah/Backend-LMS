<?php

namespace App\Http\Controllers\Api\BookOnline\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BookOnline\Model\BookOnline;
use Illuminate\Http\Request;



use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class BookOnlineController extends Controller
{
    public function getAllBookOnline(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = BookOnline::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();



        // Apply Pagination
        $bookOnline = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $bookOnline->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $bookOnline->currentPage(),
            'last_page' => $bookOnline->lastPage(),
        ], 200);
    }


    public function postBookOnline(Request $request)
    {
        // Post request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'url' => 'required|string|max:2048'
        ]);

        $bookOnline = BookOnline::create($request->all()); // Create a new Book Online instance
        return response()->json([
            'message' => 'Successfully created',
            'bookOnline' => $bookOnline // Return the created book online data
        ], 201);
    }

    public function getBookOnline(string $bookOnline_id)
    {
        // Find the specific resource
        $bookOnline = BookOnline::find($bookOnline_id); // Use the correct model name
        if (!$bookOnline) {
            return response()->json(['message' => 'Book Online not found'], 404); // Handle not found cases
        }
        return $bookOnline;
    }

    public function updateBookOnline(Request $request, string $bookOnline_id)
    {
        // Update the resource
        $bookOnline = BookOnline::find($bookOnline_id); // Use the correct model name
        if (!$bookOnline) {
            return response()->json(['message' => 'Book Online not found'], 404); // Handle not found cases
        }
        $bookOnline->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'bookOnline' => $bookOnline // Return the updated publisher data
        ], 200);
    }

    public function destroyBookOnline(string $bookOnline_id)
    {
        // Delete the resource
        $bookOnline = BookOnline::find($bookOnline_id); // Use the correct model name
        if (!$bookOnline) {
            return response()->json(['message' => 'Book Online not found'], 404); // Handle not found cases
        }
        $bookOnline->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
