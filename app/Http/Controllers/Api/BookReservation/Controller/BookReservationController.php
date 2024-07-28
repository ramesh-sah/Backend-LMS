<?php

namespace App\Http\Controllers\Api\BookReservation\Controller;

use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BookReservation\Model\BookReservation;
use Illuminate\Http\Request;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

class BookReservationController extends Controller
{

    public function getAllBookReservation(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = BookReservation::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();

        // Eager load relationships
        $query->with('bookForeign.bookPurchaseForeign.coverImageForeign', 'bookForeign.bookPurchaseForeign.bookOnlineForeign', 'bookForeign.bookPurchaseForeign.barcodeForeign', 'bookForeign.bookPurchaseForeign.authorForeign', 'bookPurchaseForeign.categoryForeign', 'bookForeign.bookPurchaseForeign.publisherForeign', 'bookForeign.bookPurchaseForeign.isbnForeign');


        // Apply Pagination
        $bookReservation = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $bookReservation->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $bookReservation->currentPage(),
            'last_page' => $bookReservation->lastPage(),
        ], 200);
    }




    public function postBookReservation(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,book_id',
            'member_id' => 'exists:members,member_id',
            'employee_id' => 'exists:employees,employee_id',
            'reservation_status' => 'required|in:pending,approved,rejected,expired'
        ]);
        $book = Book::find($request->book_id);
        if ($book->book_status !== 'available') {
            return response()->json([
                'message' => 'Book is not available',
            ], 400);
        }

        // Update the book status to reserved
        $book->book_status = 'reserved';
        $book->save();


        $reservation = BookReservation::create($request->all()); // Create a new Book Reservation instance
        return response()->json([[
            'message' => 'Successfully created',
            'reservation' => $reservation // Return the created book reservation data
        ], 201]);
    }

    public function getBookReservation(string $reservation_id)
    {
        // Find the specific resource with eager loading of relationships
        $bookReservation = BookReservation::with([
            'bookForeign.bookPurchaseForeign.coverImageForeign',
            'bookForeign.bookPurchaseForeign.bookOnlineForeign',
            'bookForeign.bookPurchaseForeign.barcodeForeign',
            'bookForeign.bookPurchaseForeign.authorForeign',
            'bookForeign.bookPurchaseForeign.categoryForeign',
            'bookForeign.bookPurchaseForeign.publisherForeign',
            'bookForeign.bookPurchaseForeign.isbnForeign'
        ])->find($reservation_id);

        if (!$bookReservation) {
            return response()->json(['message' => 'Book Reservation not found'], 404); // Handle not found cases
        }

        // Return the book along with its relationships
        return response()->json($bookReservation, 200);
    }

    public function getSpecificUserAllBookReservation(string $member_id)
    {
        // Find the specific resource with eager loading of relationships
        $bookReservation = BookReservation::where('member_id', $member_id)
            ->with([
                'bookForeign.bookPurchaseForeign.coverImageForeign',
                'bookForeign.bookPurchaseForeign.bookOnlineForeign',
                'bookForeign.bookPurchaseForeign.barcodeForeign',
                'bookForeign.bookPurchaseForeign.authorForeign',
                'bookForeign.bookPurchaseForeign.categoryForeign',
                'bookForeign.bookPurchaseForeign.publisherForeign',
                'bookForeign.bookPurchaseForeign.isbnForeign'
            ])->get();

        if ($bookReservation->isEmpty()) {
            return response()->json(['message' => 'No reservations found'], 404);
        }

        // Return the book along with its relationships
        return response()->json($bookReservation, 200);
    }
    public function updateBookReservation(Request $request, string $reservation_id)
    {
        // Update the resource
        $bookReservation = BookReservation::find($reservation_id); // Use the correct model name
        if (!$bookReservation) {
            return response()->json(['message' => 'Book Reservation not found'], 404); // Handle not found cases
        }

        $bookReservation->update($request->all());
        $book = Book::find($bookReservation->book_id); // Assuming you have book_id in BookReservation
        if ($book) {
            $book->book_status = 'reserved';

            $book->save(); // Save to persist the status change in the database
        }
        return response()->json([
            'message' => 'Successfully updated',
            'reservation' => $bookReservation // Return the updated book reservation data
        ], 200);
    }

    public function destroyBookReservation(string $reservation_id)
    {
        // Delete the resource
        $bookReservation = BookReservation::find($reservation_id); // Use the correct model name
        if (!$bookReservation) {
            return response()->json(['message' => 'Book Reservation not found'], 404); // Handle not found cases
        }
        $bookReservation->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
