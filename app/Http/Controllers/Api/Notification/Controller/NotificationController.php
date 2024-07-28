<?php

namespace App\Http\Controllers\Api\Notification\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Notification\Model\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getAllNotification(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 5); // Default to 10 items per page

        $query = Notification::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $notification = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $notification->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $notification->currentPage(),
            'last_page' => $notification->lastPage(),
        ], 200);
    }


    public function postNotification(Request $request)
    {
        // Post request
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|string',
            'message' => 'required|string',
        ]);

        $notification = Notification::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'publisher' => $notification // Return the created publisher data
        ], 201);
    }

    public function getNotification(string $notification_id)
    {
        // Find the specific resource
        $notification = Notification::find($notification_id); // Use the correct model name
        if (!$notification) {
            return response()->json(['message' => 'Publisher not found'], 404); // Handle not found cases
        }
        return response()->json(($notification));
    }

    public function updateNotification(Request $request, string $notification_id)
    {
        // Update the resource
        $notification = Notification::find($notification_id); // Use the correct model name
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404); // Handle not found cases
        }
        $notification->updateNotification($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'publisher' => $notification // Return the updated publisher data
        ], 200);
    }

    public function destroyNotification(string $notification_id)
    {
        // Delete the resource
        $notification = Notification::find($notification_id); // Use the correct model name
        if (!$notification) {
            return response()->json(['message' => 'Notification not found'], 404); // Handle not found cases
        }
        $notification->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
