<?php

namespace App\Http\Controllers\Api\Isbn\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Isbn\Model\Isbn;
use Illuminate\Http\Request;

class IsbnController extends Controller
{
    public function getAllIsbn(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Isbn::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $isbn = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );


      
        return response()->json([
            'data' => $isbn,
            'total' => $isbn->total(),
            'per_page' => $isbn->perPage(),
            'current_page' => $isbn->currentPage(),
            'last_page' => $isbn->lastPage(),
        ], 200);

        // Return the data as a JSON response

    }



    public function postIsbn(Request $request)
    {
        // Post request
        $request->validate([
            'isbn' => 'required|string',
        ]);

        $isbn = Isbn::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'Isbn' => $isbn // Return the created publisher data
        ], 201);
    }

    public function getIsbn(string $isbn_id)
    {
        // Find the specific resource
        $isbn = Isbn::find($isbn_id); // Use the correct model name
        if (!$isbn) {
            return response()->json(['message' => 'Isbn number not found'], 404); // Handle not found cases
        }
        return response()->json(['isbn'=>$isbn],200)
    }

    public function updateIsbn(Request $request, string $isbn_id)
    {
        // Update the resource
        $isbn = Isbn::find($isbn_id); // Use the correct model name
        if (!$isbn) {
            return response()->json(['message' => 'Publisher not found'], 404); // Handle not found cases
        }
        $isbn->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'isbn' => $isbn // Return the updated publisher data
        ], 200);
    }

    public function destroyIsbn(string $isbn_id)
    {
        // Delete the resource
        $isbn = Isbn::find($isbn_id); // Use the correct model name
        if (!$isbn) {
            return response()->json(['message' => 'Isbn number not found'], 404); // Handle not found cases
        }
        $isbn->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
