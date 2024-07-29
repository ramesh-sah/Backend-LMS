<?php

namespace App\Http\Controllers\Api\Barcode\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Barcode\Model\Barcode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function getAllBarcode(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 5); // Default to 10 items per page

        $query = Barcode::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Apply Pagination
        $barcode = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );


        // foreach ($bookPurchase as $bookPurchase) {
        //     $bookPurchase->CoverImageForeign;
        // }

        return response()->json([
            'data' => $barcode,
            'total' => $barcode->total(),
            'per_page' => $barcode->perPage(),
            'current_page' => $barcode->currentPage(),
            'last_page' => $barcode->lastPage(),
        ], 200);

        // Return the data as a JSON response

    }



    public function postBarcode(Request $request)
    {
        // Post request
        $request->validate([
            'barcode' => 'required|string', // Add validation rules

        ]);

        $barcode = Barcode::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'barcode data' => $barcode // Return the created publisher data
        ], 201);
    }

    public function getBarcode(string $barcode_id)
    {
        // Find the specific resource
        $barcode = Barcode::find($barcode_id); // Use the correct model name
        if (!$barcode) {
            return response()->json(['message' => 'barcode not found'], 404); // Handle not found cases
        }
        $barcode->bookPurchaseForeign;  // Get the foreign key data i.e-> CoverImageForeign from the Model instance function
        return response()->json(($barcode)->jsonSerialize(), 200);
    }

    public function updateBarcode(Request $request, string $barcode_id)
    {
        // Update the resource
        $barcode = Barcode::find($barcode_id); // Use the correct model name
        if (!$barcode) {
            return response()->json(['message' => 'Barcode not found'], 404); // Handle not found cases
        }
        $barcode->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'publisher' => $barcode // Return the updated barcode data
        ], 200);
    }

    public function destroyBarcode(string $barcode_id)
    {
        // Delete the resource
        $barcode = Barcode::find($barcode_id); // Use the correct model name
        if (!$barcode) {
            return response()->json(['message' => 'Barcode not found'], 404); // Handle not found cases
        }
        $barcode->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
