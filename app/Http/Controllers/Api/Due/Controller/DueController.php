<?php

namespace App\Http\Controllers\Api\Due\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Due\Model\Due;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;
use App\Http\Controllers\Helpers\Sort\SortHelper;
use Illuminate\Http\Request;

class DueController extends Controller
{
    public function getTotalDue(string $member_id)
    {
        $sum = Due::where('member_id', $member_id)
            ->where('due_status', 'pending')
            ->sum('amount');

        return response($sum, 200);
    }

    // public function getDueOfMember(Request $request)
    // {
    //     $sortBy = $request->input('sort_by'); // sort_by params 
    //     $sortOrder = $request->input('sort_order'); // sort_order params
    //     $filters = $request->input('filters'); // filter params
    //     $perPage = $request->input('per_page', 10); // Default to 10 items per page
    //     $currentPage = $request->input('page', 1); // Default to page 1

    //     $query = Due::query();

    //     // Apply Sorting
    //     $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

    //     // Apply Filtering
    //     $query = FilterHelper::applyFiltering($query, $filters);

    //     // Get Total Count for Pagination
    //     $total = $query->count();

    //     // Get the paginated result
    //     $due = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

    //     // Apply Pagination
    //     $due = PaginationHelper::applyPagination(
    //         $due,
    //         $perPage,
    //         $request->input('page', 1), // Default to page 1
    //         $currentPage,
    //         $total,
    //     );

    //     // Return the data as a JSON response
    //     return response()->json([
    //         'data' => $due->toArray(),
    //         'total' => $total,
    //         'per_page' => $perPage,
    //         'current_page' => $due->currentPage(),
    //         'last_page' => $due->lastPage(),
    //     ], 200);
    // }


    public function getDueOfMember(Request $request, string $member_id)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params


        // Find the specific resource with eager loading of relationships
        $dues = Due::where('member_id', $member_id)->get(); // Use the correct model name

        if ($dues->isEmpty()) {
            return response()->json(['message' => 'No issue found'], 404);
        }

        // Apply Sorting
        $dues = SortHelper::applySorting($dues, $sortBy, $sortOrder);

        // // Apply Filtering
        $dues = FilterHelper::applyFiltering($dues, $filters);

        // Return the book along with its relationships
        return response(["data" => $dues], 200);
    }

    public function index()
    {
        // Fetch all the Publisher objects
        return Due::all();
        // $dues = $query->simplePaginate(10);// Use the correct model name
    }

    public function store(Request $request)
    {
        // Post request
        $request->validate([
            'description' => 'required|string',
            'amount' => 'required|integer',
            'due_status' => 'required|in:cleared,pending',
            'member_id' => 'required|exist:members',
            'employee_id' => 'exist:employees',
            'book_id' => 'exist:books',
            'issue_id' => 'exist:issues'
        ]);

        $due = Due::create($request->all()); // Create a new Due instance
        return response()->json([
            'message' => 'Successfully created',
            'due' => $due // Return the created due data
        ], 201);
    }

    public function show(string $due_id)
    {
        // Find the specific resource
        $due = Due::find($due_id); // Use the correct model name
        if (!$due) {
            return response()->json(['message' => 'Due not found'], 404); // Handle not found cases
        }
        return $due;
    }

    public function update(Request $request, string $due_id)
    {
        // Update the resource
        $due = Due::find($due_id); // Use the correct model name
        if (!$due) {
            return response()->json(['message' => 'Due not found'], 404); // Handle not found cases
        }
        $due->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'due' => $due // Return the updated due data
        ], 200);
    }

    public function destroy(string $due_id)
    {
        // Delete the resource
        $due = Due::find($due_id); // Use the correct model name
        if (!$due) {
            return response()->json(['message' => 'Due not found'], 404); // Handle not found cases
        }
        $due->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
