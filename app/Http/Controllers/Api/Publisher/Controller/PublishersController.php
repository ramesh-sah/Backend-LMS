<?php

namespace App\Http\Controllers\Api\Publisher\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Publisher\Model\Publishers;
use Illuminate\Http\Request;

class PublishersController extends Controller
{
    public function getAllPublisher(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 5); // Default to 10 items per page

        $query = Publishers::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $publishers = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([[
            'data' => $publishers->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $publishers->currentPage(),
            'last_page' => $publishers->lastPage(),
        ], 200]);
    }


    public function postPublisher(Request $request)
    {
        // Post request
        $request->validate([
            'publisher_name' => 'required|string|',
            'publication_place' => 'required|string',
        ]);

        $publisher = Publishers::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created',
            'publisher' => $publisher // Return the created publisher data
        ], 201]);
    }

    public function getPublisher(string $publisher_id)
    {
        // Find the specific resource
        $publisher = Publishers::find($publisher_id); // Use the correct model name
        if (!$publisher) {
            return response()->json([['message' => 'Publisher not found'], 404]); // Handle not found cases
        }
        return response()->json([
            'message'=>"Publisher Found",
            'publisher' => $publisher,
            200]);
    }

    public function updatePublisher(Request $request, string $publisher_id)
    {
        // Update the resource
        $publisher = Publishers::find($publisher_id); // Use the correct model name
        if (!$publisher) {
            return response()->json([['message' => 'Publisher not found'], 404]); // Handle not found cases
        }
        $publisher->update($request->all());
        return response()->json([[
            'message' => 'Successfully updated',
            'publisher' => $publisher // Return the updated publisher data
        ], 200]);
    }

    public function destroyPublisher(string $publisher_id)
    {
        // Delete the resource
        $publisher = Publishers::find($publisher_id); // Use the correct model name
        if (!$publisher) {
            return response()->json([['message' => 'Publisher not found'], 404]); // Handle not found cases
        }
        $publisher->delete();
        return response()->json([[
            'message' => 'Successfully deleted'
        ], 200]);
    }
}
