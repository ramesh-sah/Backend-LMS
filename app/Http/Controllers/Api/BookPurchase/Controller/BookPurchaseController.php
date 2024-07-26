<?php

namespace App\Http\Controllers\Api\BookPurchase\Controller;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookPurchaseController extends Controller
{


    public function getAllBookPurchase(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = BookPurchase::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        $query->with('coverImageForeign', 'bookOnlineForeign', 'barcodeForeign', 'authorForeign', 'categoryForeign', 'publisherForeign', 'isbnForeign');
        // Get the paginated result
        $bookPurchases = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data
        foreach ($bookPurchases as $bookPurchase) {
            $bookPurchase->coverImageForeign;  // Get the foreign key data
            $bookPurchase->bookOnlineForeign;  // Get the foreign key data
            $bookPurchase->barcodeForeign;     // Get the foreign key data
            $bookPurchase->authorForeign;      // Get the foreign key data
            $bookPurchase->categoryForeign;    // Get the foreign key data
            $bookPurchase->publisherForeign;   // Get the foreign key data
            $bookPurchase->isbnForeign;        // Get the foreign key data
        }

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $bookPurchases,
            $perPage,
            $currentPage,
            $total
        );

        return response()->json([
            'data' => $paginatedResult->array(),
            'total' => $paginatedResult->total(),
            'per_page' => $paginatedResult->perPage(),
            'current_page' => $paginatedResult->currentPage(),
            'last_page' => $paginatedResult->lastPage(),
        ], 200);
    }


    public function postBookPurchase(Request $request)
    {
        // Post request
        $request->validate([
            'class_number' => 'required|string',
            'book_number' => 'required|string',
            'title' => 'required|string',
            'sub_title' => 'string|nullable',
            'edition_statement' => 'string|nullable',
            'number_of_pages' => 'required|string',
            'publication_year' => 'required|string',
            'series_statement' => 'string|nullable',
            'quantity' => 'required|integer',
            'image_id' => 'required|string|exists:cover_images,image_id',
            'online_id' => 'string|exists:book_onlines,online_id',
            'barcode_id' => 'required|string|exists:barcodes,barcode_id',
            'author_id' => 'required|string|exists:authors,author_id',
            'category_id' => 'required|string|exists:categories,category_id',
            'publisher_id' => 'required|string|exists:publishers,publisher_id',
            'isbn_id' => 'required|string|exists:isbns,isbn_id',

        ]);

        $bookPurchase = BookPurchase::create($request->all()); // Create a new Publisher instance
        return response()->json([[
            'message' => 'Successfully created book purchase',
            'bookPurchase' => $bookPurchase->jsonSerialize() // Return the created publisher data
        ], 201]);
    }

    public function getBookPurchase(string $purchase_id)
    {
        // Find the specific resource
        $bookPurchase = BookPurchase::find($purchase_id); // Use the correct model name
        if (!$bookPurchase) {
            return response()->json([['message' => 'Book Purchased not found'], 404]); // Handle not found cases
        }
        $bookPurchase->coverImageForeign;  // Get the foreign key data i.e-> CoverImageForeign from the Model instance function
        $bookPurchase->bookOnlineForeign; // Get the foreign key data
        $bookPurchase->barcodeForeign; // Get the foreign key data
        $bookPurchase->authorForeign; // Get the foreign key data
        $bookPurchase->categoryForeign; // Get the foreign key data
        $bookPurchase->publisherForeign; // Get the foreign key data
        $bookPurchase->isbnForeign; // Get the foreign key data
        return response()->json([($bookPurchase)->jsonSerialize(), 200]);
    }

    public function updateBookPurchase(Request $request, string $purchase_id)
    {
        // Update the resource
        $bookPurchase = BookPurchase::find($purchase_id); // Use the correct model name
        if (!$bookPurchase) {
            return response()->json([['message' => 'Book Purchased not found'], 404]); // Handle not found cases
        }
        $bookPurchase->update($request->all());
        return response()->json([[
            'message' => 'Successfully updated',
            'publisher' => $bookPurchase->jsonSerialize() // Return the updated publisher data
        ], 200]);
    }

    public function destroyBookPurchase(string $purchase_id)
    {
        // Delete the resource
        $bookPurchase = BookPurchase::find($purchase_id); // Use the correct model name
        if (!$bookPurchase) {
            return response()->json([['message' => 'Purchased Book  not found'], 404]); // Handle not found cases
        }


        return response()->json([[
            'message' => 'Successfully deleted '
        ], 200]);
    }
}
