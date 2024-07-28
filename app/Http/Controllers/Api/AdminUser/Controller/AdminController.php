<?php

namespace App\Http\Controllers\Api\AdminUser\Controller;


use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;


use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AdminUser\Model\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    /**
     * Register a new admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerAdmin(Request $request)
    {
        $request->validate([

            'username' => 'required|string|unique:admins,username',

            'email' => 'required|email|regex:/@patancollege\.edu\.np$/|unique:admins,email',
            'email_verified_at' => 'nullable|date',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'mobile' => 'nullable|string',
            'status' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $admin = Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'mobile' => $request->mobile,
            'status' => $request->status,
            'is_active' => $request->is_active,
        ]);

        $token = $admin->createToken('admin token', ['admin'])->plainTextToken; //created the admin token

        return response()->json([
            'data' => [
                'admin user' => $admin->jsonSerialize(),
                'token' => $token,
            ]
        ], 201);
    }

    public function logoutAdmin(Request $request)
    {
        //check and  validate the token and then delete the token
        $token = $request->user('admin')->currentAccessToken();
        $request->user('admin')->tokens()->where('id', $token->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Admin user logged out',
        ], 200);
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return response()->json([
                'message' => 'Admin not found or Invalid email provided.',
            ], 404);
        }

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'message' => 'Invalid password provided.',
            ], 401);
        }

        $token = $admin->createToken('mytoken', ['admin'])->plainTextToken; //created the admin token after the login

        return response()->json([
            'adminuser' => $admin->toArray(),
            'token' => $token,
        ], 200);
    }
    public function getAllAdmin(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Admin::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();



        // Apply Pagination
        $admin = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $admin->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $admin->currentPage(),
            'last_page' => $admin->lastPage(),
        ], 200);
    }


    public function getAdmin(string $admin_id)
    {
        // Find the specific resource
        $admin = Admin::find($admin_id); // Use the correct model name
        if (!$admin) {
            return response()->json(['message' =>  'admin not found'], 404); // Handle not found cases
        }


        return response()->json($admin);
    }

    public function updateAdmin(Request $request, string $admin_id)
    {
        // Update the resource
        $admin = Admin::find($admin_id); // Use the correct model name
        if (!$admin) {
            return response()->json(['message' => 'admin not found'], 404); // Handle not found cases
        }

        $admin->update($request->all());

        return response()->json([
            'message' => 'Successfully updated',
            'admin' => $admin // Return the updated book reservation data
        ], 200);
    }

    public function destroyAdmin(string $admin_id)
    {
        // Delete the resource
        $admin = Admin::find($admin_id); // Use the correct model name
        if (!$admin) {
            return response()->json(['message' => 'admin not found'], 404); // Handle not found cases
        }
        $admin->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
