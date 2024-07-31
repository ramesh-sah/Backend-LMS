<?php

namespace App\Http\Controllers\Api\Book\Controller;

use App\Http\Controllers\Api\Author\Model\Author;
use App\Http\Controllers\Api\Barcode\Model\Barcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Book\Model\Book;
use App\Http\Controllers\Api\BookOnline\Model\BookOnline;
use App\Http\Controllers\Api\BookPurchase\Model\BookPurchase;
use App\Http\Controllers\Api\Category\Model\Category;
use App\Http\Controllers\Api\CoverImage\Model\CoverImage;
use App\Http\Controllers\Api\Isbn\Model\Isbn;
use App\Http\Controllers\Api\Publisher\Model\Publishers;
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

    public function addBook(Request $request)
    {
        // Post request
        $request->validate([
            'class_number' => 'string',
            'book_number' => 'string',
            'title' => 'string',
            'sub_title' => 'string|nullable',
            'edition_statement' => 'string|nullable',
            'number_of_pages' => 'string',
            'publication_year' => 'string',
            'series_statement' => 'nullable',
            'quantity' => 'integer',

            //cover_images table validation
            'link' => 'url',
            //book_online table validation
            "name" => 'string',
            "price" => 'string',
            "url" => 'string',
            //barcode table validation
            'barcode' => 'string',

            //author table validation
            'author_first_name' => 'string',
            'author_middle_name' => 'string',
            'author_last_name' => 'string',

            //category table validation
            'category_name' => 'string',

            //publisher table validation
            'publisher_name' => 'string',
            'publication_place' => 'string',
            //isbn table validation
            'isbn' => 'required|string',
            // 'image_id' => 'required|exists:cover_images,image_id',
            // 'online_id' => 'exists:book_onlines,online_id',
            // 'barcode_id' => 'required|exists:barcodes,barcode_id',
            // 'author_id' => 'required|exists:authors,author_id',
            // 'category_id' => 'required|exists:categories,category_id',
            // 'publisher_id' => 'required|exists:publishers,publisher_id',
            // 'isbn_id' => 'required|exists:isbns,isbn_id',


        ]);


        // Create related records
        $coverImage = CoverImage::create($request->only('link')); // Only create with 'link'
        $bookOnline = BookOnline::create($request->only('name', 'price', 'url'));
        $barcode = Barcode::create($request->only('barcode'));
        $author = Author::create($request->only('author_first_name', 'author_middle_name', 'author_last_name'));
        $category = Category::create($request->only('category_name'));
        $publisher = Publishers::create($request->only('publisher_name', 'publication_place'));
        $isbn = Isbn::create($request->only('isbn'));

        // Create the BookPurchase record
        $bookPurchase = BookPurchase::create([
            'class_number' => $request->class_number,
            'book_number' => $request->book_number,
            'title' => $request->title,
            'sub_title' => $request->sub_title,
            'edition_statement' => $request->edition_statement,
            'number_of_pages' => $request->number_of_pages,
            'publication_year' => $request->publication_year,
            'series_statement' => $request->series_statement,
            'quantity' => $request->quantity,
            'image_id' => $coverImage->image_id, // Use the ID of the created CoverImage
            'online_id' => $bookOnline->online_id, // Use the ID of the created BookOnline
            'barcode_id' => $barcode->barcode_id, // Use the ID of the created Barcode
            'author_id' => $author->author_id, // Use the ID of the created Author
            'category_id' => $category->category_id, // Use the ID of the created Category
            'publisher_id' => $publisher->publisher_id, // Use the ID of the created Publisher
            'isbn_id' => $isbn->isbn_id, // Use the ID of the created Isbn
        ]);
        $books = Book::create([
            'purchase_id' => $bookPurchase->purchase_id,
        ]);

        return response()->json([
            'message' => 'Book purchase created successfully',
            'bookPurchase' => $bookPurchase,
            'book' => $books,
        ]);
    }
}
