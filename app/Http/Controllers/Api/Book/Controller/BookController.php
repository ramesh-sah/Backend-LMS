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
        $sortBy = $request->input('sort_by', 'default_column'); // default sorting column
        $sortOrder = $request->input('sort_order', 'asc'); // default sorting order
        $filters = $request->input('filters', []); // default to empty filters
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Book::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Eager load relationships
        $query->with('bookPurchaseForeign.coverImageForeign', 'bookPurchaseForeign.bookOnlineForeign', 'bookPurchaseForeign.barcodeForeign', 'bookPurchaseForeign.authorForeign', 'bookPurchaseForeign.categoryForeign', 'bookPurchaseForeign.publisherForeign', 'bookPurchaseForeign.isbnForeign');

        // Paginate the results
        $books = $query->paginate($perPage);

        return response()->json([
            'data' => $books->items(),
            'total' => $books->total(),
            'per_page' => $books->perPage(),
            'current_page' => $books->currentPage(),
            'last_page' => $books->lastPage(),
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
