<?php

namespace App\Http\Controllers\Api\Member\Controller;

use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Member\Model\Member;
use Illuminate\Support\Facades\Hash;

class MemberController extends BaseController
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerMember(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'dob' => 'required|date',
            'roll_number' => 'required|integer|unique:members,roll_number',
            'address' => 'required|string|max:500',
            'gender' => 'required|in:male,female,other',
            'email' => 'required|email|regex:/@patancollege\.edu\.np$/|unique:members,email',
            'password' => 'required|string|min:8',
            'contact_number' => 'required|digits:10',
            'enrollment_year' => 'required|integer|min:1900|max:' . date('Y'),
            'image_link' => 'nullable|url',
        ]);

        $member = Member::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'roll_number' => $request->roll_number,
            'address' => $request->address,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'enrollment_year' => $request->enrollment_year,
            'image_link' => $request->image_link,

        ]);

        $token = $member->createToken('mytoken', ['member'])->plainTextToken;

        return response()->json(
            // Wrap the data in an array
            [
                'member' => $member,
                'token' => $token,
            ],
            201
        );
    }

    public function logoutMember(Request $request)
    {
        $token = $request->user()->currentAccessToken();
        $request->user()->tokens()->where('id', $token->id)->delete();

        return response()->json(

            [
                'success' => true,
                'message' => 'User logged out',

            ],
            200
        );
    }

    public function loginMember(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $member = Member::where('email', $request->email)->first();

        if (!$member) {
            return response()->json([
                'message' => 'User not found or Invalid email provided.',
            ], 404);
        }

        if (!$member || !Hash::check($request->password, $member->password)) {
            return response()->json([
                'message' => 'Invalid password provided.',
            ], 401);
        }

        $token = $member->createToken('mytoken', ['member'])->plainTextToken;

        return response()->json([
            [
                'user' => $member,
                'token' => $token,
            ]
        ], 200);
    }


    public function getAllMember(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Member::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        // $total = $query->count();

        // Apply Pagination
        $members = $query->paginate($perPage);

        // Return the data as a JSON response
        return response()->json([
            'data' => $members->items(),
            'total' => $members->total(),
            'per_page' => $members->perPage(),
            'current_page' => $members->currentPage(),
            'last_page' => $members->lastPage(),
        ], 200);
    }

    public function getMember(string $member_id)
    {
        // Find the specific resource
        $member = Member::find($member_id); // Use the correct model name
        if (!$member) {
            return response()->json(['message' =>  'member not found'], 404); // Handle not found cases
        }


        return response()->json([$member]);
    }

    public function updateMember(Request $request, string $member_id)
    {
        // Update the resource
        $member = Member::find($member_id); // Use the correct model name
        if (!$member) {
            return response()->json(['message' => 'member not found'], 404); // Handle not found cases
        }

        $member->update($request->all());

        return response()->json([
            'message' => 'Successfully updated',
            'member' => $member // Return the updated book reservation data
        ], 200);
    }

    public function destroyMember(string $member_id)
    {
        // Delete the resource
        $member = Member::find($member_id); // Use the correct model name
        if (!$member) {
            return response()->json(['message' => 'member not found'], 404); // Handle not found cases
        }
        $member->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
