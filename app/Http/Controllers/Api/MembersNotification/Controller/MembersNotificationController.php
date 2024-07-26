<?php

namespace App\Http\Controllers\Api\MembersNotification\Controller;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\MembersNotification\Model\MembersNotification;
use Illuminate\Http\Request;

class MembersNotificationController extends Controller
{
    public function getAllMembersNotification(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 5); // Default to 10 items per page

        $query = MembersNotification::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $membersNotification = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );


        // foreach ($bookPurchase as $bookPurchase) {
        //     $bookPurchase->CoverImageForeign;
        // }

        return response()->json([[
            'data' => $membersNotification,
            'total' => $membersNotification->total(),
            'per_page' => $membersNotification->perPage(),
            'current_page' => $membersNotification->currentPage(),
            'last_page' => $membersNotification->lastPage(),
        ], 200]);

        // Return the data as a JSON response

    }


    public function postMembersNotification(Request $request)
    {
        // Post request
        $request->validate([
            'member_id' => 'required|string|exists:members,member_id',
            'notification_id' => 'required|string|exists:notifications,notification_id',
            'isRead' => 'boolean',
        ]);

        $membersNotification = MembersNotification::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created',
            'memebersNotification' => $membersNotification // Return the created publisher data
        ], 201]);
    }

    public function getMembersNotification(string $member_notification_id)
    {
        // Find the specific resource
        $membersNotification = MembersNotification::find($member_notification_id); // Use the correct model name
        if (!$membersNotification) {
            return response()->json([['message' => 'memeber not found'], 404]); // Handle not found cases
        }
        $membersNotification->memberForeign;
        $membersNotification->notificationForeign;
        return response()->json([($membersNotification)->jsonSerialize(), 200]);
    }

    public function updateMembersNotification(Request $request, string $member_notification_id)
    {
        // Update the resource
        $membersNotification = MembersNotification::find($member_notification_id); // Use the correct model name
        if (!$membersNotification) {
            return response()->json(['message' => 'Member Notification  not found'], 404); // Handle not found cases
        }
        $membersNotification->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'member notification' => $membersNotification // Return the updated publisher data
        ], 200);
    }

    public function destroyMembersNotification(string $member_notification_id)
    {
        // Delete the resource
        $membersNotification = MembersNotification::find($member_notification_id); // Use the correct model name
        if (!$membersNotification) {
            return response()->json([['message' => 'Publisher not found'], 404]); // Handle not found cases
        }
        $membersNotification->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
