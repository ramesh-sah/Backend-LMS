<?php

namespace App\Http\Controllers\Api\Publisher\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Publisher\Model\Publishers;
use Illuminate\Http\Request;

class PublishersController extends Controller
{
    public function index()
    {
        // Fetch all the Publisher objects
        return Publishers::all();
        // $publishers = $query->simplePaginate(10);// Use the correct model name
    }

    public function store(Request $request)
    {
        // Post request
        $request->validate([
            'publisher_name', // Add validation rules
            'publication_place',
        ]);

        $publisher = Publishers::create($request->all()); // Create a new Publisher instance
        return response()->json([
            'message' => 'Successfully created',
            'publisher' => $publisher // Return the created publisher data
        ], 201);
    }

    public function show(string $publisher_id)
    {
        // Find the specific resource
        $publisher = Publishers::find($publisher_id); // Use the correct model name
        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404); // Handle not found cases
        }
        return $publisher;
    }

    public function update(Request $request, string $id)
    {
        // Update the resource
        $publisher = Publishers::find($id); // Use the correct model name
        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404); // Handle not found cases
        }
        $publisher->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'publisher' => $publisher // Return the updated publisher data
        ], 200);
    }

    public function destroy(string $id)
    {
        // Delete the resource
        $publisher = Publishers::find($id); // Use the correct model name
        if (!$publisher) {
            return response()->json(['message' => 'Publisher not found'], 404); // Handle not found cases
        }
        $publisher->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
