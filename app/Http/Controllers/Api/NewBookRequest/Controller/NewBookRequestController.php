<?php

namespace App\Http\Controllers\Api\NewBookRequest\Controller;

use App\Http\Controllers\Api\NewBookRequest\Model\NewBookRequest;
use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Publisher\Model\Publishers;
use Illuminate\Http\Request;

class NewBookRequestController extends Controller
{

    public function getAllNewBookRequest(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = NewBookRequest::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Get the paginated result
        $bookPurchases = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data
        foreach ($bookPurchases as $bookPurchase) {
            $bookPurchase->memberForeign;  // Get the foreign key data
            $bookPurchase->employeeForeign;  // Get the foreign key data

        }

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $bookPurchases,
            $perPage,
            $currentPage,
            $total
        );

        return response()->json([[
            'data' => $paginatedResult->items(),
            'total' => $paginatedResult->total(),
            'per_page' => $paginatedResult->perPage(),
            'current_page' => $paginatedResult->currentPage(),
            'last_page' => $paginatedResult->lastPage(),
        ], 200]);
    }

    public function postNewBookRequest(Request $request)
    {
        // Post request
        $request->validate([
            'book_name' => 'required|string',
            'author_name' => 'required|string',
            'member_id' => 'string|exists:members,member_id',
            'employee_id' => 'string|exists:employees,employee_id'
        ]);

        $newBookRequest = NewBookRequest::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created',
            'newBookRequest ' => $newBookRequest  // Return the created publisher data
        ], 201]);
    }

    public function getNewBookRequest(string $request_id)
    {
        // Find the specific resource
        $newBookRequest = NewBookRequest::find($request_id); // Use the correct model name

        if (!$newBookRequest) {
            return response()->json(['message' => 'New Book Request not found'], 404); // Handle not found cases
        }
        $newBookRequest->memberForeign;  // Get the foreign key data
        $newBookRequest->employeeForeign;
        return response()->json([$newBookRequest, 200]);
    }

    public function updateNewBookRequest(Request $request, string $request_id)
    {
        // Update the resource
        $newBookRequest = NewBookRequest::find($request_id); // Use the correct model name
        if (!$newBookRequest) {
            return response()->json(['message' => 'New Book Request not found'], 404); // Handle not found cases
        }
        $newBookRequest->update($request->all());
        return response()->json([[
            'message' => 'Successfully updated',
            'newBookRequest' => $newBookRequest // Return the updated publisher data
        ], 200]);
    }

    public function destroyNewBookRequest(string $request_id)
    {
        // Delete the resource
        $newBookRequest = NewBookRequest::find($request_id); // Use the correct model name
        if (!$newBookRequest) {
            return response()->json(['message' => 'New Book Request not found'], 404); // Handle not found cases
        }
        $newBookRequest->delete();
        return response()->json([[
            'message' => 'Successfully deleted'
        ], 200]);
    }
}
