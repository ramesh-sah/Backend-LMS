<?php

namespace App\Http\Controllers\Api\Book\Controller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class BookController extends Controller
{
    public function getAllBook(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page
        $currentPage = $request->input('page', 1); // Default to page 1

        $query = Book::query();



        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('bookPurchaseForeign.coverImageForeign', 'bookPurchaseForeign.bookOnlineForeign', 'bookPurchaseForeign.barcodeForeign', 'bookPurchaseForeign.authorForeign', 'bookPurchaseForeign.categoryForeign', 'bookPurchaseForeign.publisherForeign', 'bookPurchaseForeign.isbnForeign');


        // Get the paginated result
        $book = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        // Retrieve foreign key data
        foreach ($book as $book) {
            $book->bookPurchaseForeign;        // Get the foreign key data
        }

        // Apply Pagination Helper
        $paginatedResult = PaginationHelper::applyPagination(
            $book,
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

    public function postBook(Request $request)
    {
        // Post request
        $request->validate([
            'book_status' => 'nullable|string',
            'purchase_id' => 'required|string|exists:book_purchases,purchase_id',
        ]);

        $book = Book::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'book' => $book // Return the created publisher data
        ], 201);
    }

    public function getBook(string $book_id)
    {
        // Find the specific resource with eager loading of relationships
        $book = Book::with([
            'bookPurchaseForeign.coverImageForeign',
            'bookPurchaseForeign.bookOnlineForeign',
            'bookPurchaseForeign.barcodeForeign',
            'bookPurchaseForeign.authorForeign',
            'bookPurchaseForeign.categoryForeign',
            'bookPurchaseForeign.publisherForeign',
            'bookPurchaseForeign.isbnForeign'
        ])->find($book_id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404); // Handle not found cases
        }

        // Return the book along with its relationships
        return response()->json([$book, 200]);
    }
    public function getBookByCategory(string $category_id)
    {
        // Get the IDs of books with a non-null purchase_id
        $bookIdsWithPurchaseId = Book::whereNotNull('purchase_id')
            ->pluck('purchase_id');

        // If there are no books with a purchase_id, return a 404 response
        if ($bookIdsWithPurchaseId->isEmpty()) {
            return response()->json(['message' => 'No books found with purchase_id'], 404);
        }

        // Find the specific resource with eager loading of relationships
        $bookPurchases = BookPurchase::with([
            'coverImageForeign',
            'bookOnlineForeign',
            'barcodeForeign',
            'authorForeign',
            'categoryForeign',
            'publisherForeign',
            'isbnForeign'
        ])->whereIn('purchase_id', $bookIdsWithPurchaseId)
            ->where('category_id', $category_id)
            ->get();

        if ($bookPurchases->isEmpty()) {
            return response()->json(['message' => 'No books found'], 404);
        }

        // Return the book purchases along with their relationships
        return response()->json($bookPurchases, 200);
    }




    public function updateBook(Request $request, string $book_id)
    {
        // Update the resource
        $book = Book::find($book_id); // Use the correct model name
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404); // Handle not found cases
        }
        $book->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'book' => $book // Return the updated publisher data
        ], 200);
    }

    public function destroyBook(string $book_id)
    {
        // Delete the resource
        $book = Book::find($book_id); // Use the correct model name
        if (!$book) {
            return response()->json(['message' => 'Book not found'], 404); // Handle not found cases
        }
        $book->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
