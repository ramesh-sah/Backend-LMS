<?php

namespace App\Http\Controllers\Api\DamagedBook\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;


use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\DamagedBook\Model\DamagedBook;
use Illuminate\Http\Request;

class DamagedBookController extends Controller
{
    public function getAllDamagedBook(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = DamagedBook::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('bookForeign.bookPurchaseForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign');


        // Get the paginated result
        $damagedBook = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data
        foreach ($damagedBook as $damagedBook) {
            $damagedBook->bookForeign;      // Get the foreign key data
        }

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $damagedBook,
            $perPage,
            $currentPage,
            $total
        );

        return response()->json([
            'data' => $paginatedResult->items(),
            'total' => $paginatedResult->total(),
            'per_page' => $paginatedResult->perPage(),
            'current_page' => $paginatedResult->currentPage(),
            'last_page' => $paginatedResult->lastPage(),
        ], 200);
    }


    public function postDamagedBook(Request $request)
    {
        // Post request
        $request->validate([
            'book_id' => 'required|string|exists:books,book_id'
        ]);

        $damagedBook = DamagedBook::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'damaged book' => $damagedBook // Return the created publisher data
        ], 201);
    }
    public function getDamagedBook(string $damaged_book_id)
    {
        // Find the specific resource with eager loading of relationships
        $damagedBook = DamagedBook::with([
            'bookForeign.bookPurchaseForeign', 'bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookForeign.bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign'
        ])->find($damaged_book_id);

        if (!$damagedBook) {
            return response()->json(['message' => 'damaged book not found'], 404); // Handle not found cases
        }

        // Return the book along with its relationships
        return response()->json($damagedBook, 200);
    }



    public function updateDamagedBook(Request $request, string $damaged_book_id)
    {
        // Update the resource
        $damagedBook = DamagedBook::find($damaged_book_id); // Use the correct model name
        if (!$damagedBook) {
            return response()->json(['message' => 'damaged book  not found'], 404); // Handle not found cases
        }
        $damagedBook->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'publisher' => $damagedBook // Return the updated publisher data
        ], 200);
    }

    public function destroyDamagedBook(string $damaged_book_id)
    {
        // Delete the resource
        $damagedBook = DamagedBook::find($damaged_book_id); // Use the correct model name
        if (!$damagedBook) {
            return response()->json(['message' => 'damaged book not found'], 404); // Handle not found cases
        }
        $damagedBook->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
