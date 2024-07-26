<?php

namespace App\Http\Controllers\Api\Membership\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Membership\Model\Membership;
use Illuminate\Http\Request;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class MembershipController extends Controller
{
    public function getAllMembership(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = Membership::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships



        // Get the paginated result
        $membership = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data
        foreach ($membership as $membership) {
            $membership->memberForeign;        // Get the foreign key data
            $membership->employeeForeign;
        }

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $membership,
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


    public function postMembership(Request $request)
    {
        // Post request
        $request->validate([
            'membership_status' => 'string',
            'member_id' => 'exists:members,member_id',
            'employee_id' => 'exists:employees,employee_id',
            'expiry_date' => 'date'


        ]);

        $membership = Membership::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created',
            'membership data' => $membership // Return the created publisher data
        ], 201]);
    }

    public function getMembership(string $membership_id)
    {
        // Find the specific resource
        $membership = Membership::find($membership_id); // Use the correct model name
        if (!$membership) {
            return response()->json([['message' => 'Membership not found'], 404]); // Handle not found cases
        }
        $membership->memberForeign;        // Get the foreign key data
        $membership->employeeForeign;
        return response()->json($membership);
    }

    public function getUserMembership(string $member_id)
    {
        // Find the specific resource using the correct model name
        $membership = Membership::where('member_id', $member_id)->first(); // Use `where` to search by `member_id`

        if (!$membership) {
            return response()->json(['message' => 'Membership not found'], 404); // Handle not found cases
        }

        // Assuming you have defined relationships `memberForeign` and `employeeForeign` in your Membership model
        $membership->load('memberForeign', 'employeeForeign'); // Eager load the foreign key relationships

        return response()->json($membership);
    }


    public function updateMembership(Request $request, string $membership_id)
    {
        // Update the resource
        $membership = Membership::find($membership_id); // Use the correct model name
        if (!$membership) {
            return response()->json([['message' => 'Membership  not found'], 404]); // Handle not found cases
        }
        $membership->update($request->all());
        return response()->json([[
            'message' => 'Successfully updated',
            'membership ' => $membership // Return the updated publisher data
        ], 200]);
    }

    public function destroyMembership(string $membership_id)
    {
        // Delete the resource
        $membership = Membership::find($membership_id); // Use the correct model name
        if (!$membership) {
            return response()->json([['message' => 'Membership not found'], 404]); // Handle not found cases
        }
        $membership->delete();
        return response()->json([[
            'message' => 'Successfully deleted'
        ], 200]);
    }
}
