<?php

namespace App\Http\Controllers\Api\Issue\Controller;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\Issue\Model\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function index()
    {
        // Fetch all the Issue objects
        return Issue::all();
        // $issues = $query->simplePaginate(10);// Use the correct model name
    }

    public function store(Request $request)
    {
        // Post request
        $request->validate([
            'due_date' => now()->addDays(14),
            'member_id' => 'required|exists:members,member_id',
            'book_id' => 'required|exists:books,book_id',
        ]);

        $issue = Issue::create($request->all()); // Create a new Issue instance
        return response()->json([
            'message' => 'Successfully created',
            'issue' => $issue // Return the created issue data
        ], 201);
    }

    public function show(string $issue_id)
    {
        // Find the specific resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        return $issue;
    }

    public function update(Request $request, string $issue_id)
    {
        // Update the resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        $issue->update($request->all());
        return response()->json([
            'message' => 'Successfully updated',
            'issue' => $issue // Return the updated issue data
        ], 200);
    }

    public function destroy(string $issue_id)
    {
        // Delete the resource
        $issue = Issue::find($issue_id); // Use the correct model name
        if (!$issue) {
            return response()->json(['message' => 'Issue not found'], 404); // Handle not found cases
        }
        $issue->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
