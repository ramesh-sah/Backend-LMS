<?php

namespace App\Http\Controllers\Api\Employee\Controller;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\Employee\Model\Employee;
use Illuminate\Http\Request;



use App\Http\Controllers\Helpers\Sort\SortHelper;
use App\Http\Controllers\Helpers\Filters\FilterHelper;
use App\Http\Controllers\Helpers\Pagination\PaginationHelper;

use Illuminate\Support\Facades\Hash;

class EmployeeController extends BaseController
{
    /**
     * Register a new admin user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerEmployee(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string',
            'middle_name' => 'required|string',
            'last_name' => 'required|string',
            'dob' => 'required',
            'username' => 'required|string|unique:employees,username',
            'email' => 'required|email|regex:/@patancollege\.edu\.np$/|unique:employees,email',
            'email_verified_at' => 'nullable|date',
            'password' => 'required|string|min:8',
            'address' => 'required|string',
            'role' => 'required|string',
            'gender' => 'required|in:male,female,other',
            'contact_number' => 'required|digits:10',
            'enrollment_year' => 'required',
            'account_status' => 'required|in:active,inactive,suspended',
            'image_link' => 'nullable|url',
        ]);


        $employee = Employee::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'dob' => $request->dob,
            'username' => $request->username,
            'email' => $request->email,
            'email_verified_at' => $request->email_verified_at,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'role' => $request->role,
            'contact_number' => $request->contact_number,
            'enrollment_year' => $request->enrollment_year,
            'account_status' => $request->account_status,
            'image_link' => $request->image_link,
        ]);

        $token = $employee->createToken('admin token', ['employee'])->plainTextToken; //created the admin token

        return response()->json([
            'data' => [
                'employee data' => $employee->jsonSerialize(),
                'token' => $token,
            ]
        ], 201);
    }

    public function logoutEmployee(Request $request)
    {
        //check and  validate the token and then delete the token
        $token = $request->user('employee')->currentAccessToken();
        $request->user('employee')->tokens()->where('id', $token->id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee  logged out successfully',
        ], 200);
    }

    public function loginEmployee(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $employee = Employee::where('email', $request->email)->first();

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found or Invalid email provided.',
            ], 404);
        }

        if (!$employee || !Hash::check($request->password, $employee->password)) {
            return response()->json([
                'message' => 'Invalid password provided.',
            ], 401);
        }

        $token = $employee->createToken('mytoken', ['employee'])->plainTextToken; //created the admin token after the login

        return response()->json([
            'employee user' => $employee,
            'token' => $token,
        ], 200);
    }


    public function getAllEmployee(Request $request)
    {
        $sortBy = $request->input('sort_by'); // sort_by params 
        $sortOrder = $request->input('sort_order'); // sort_order params
        $filters = $request->input('filters'); // filter params
        $perPage = $request->input('per_page', 10); // Default to 10 items per page

        $query = Employee::query();

        // Apply Sorting
        $query = SortHelper::applySorting($query, $sortBy, $sortOrder);

        // Apply Filtering
        $query = FilterHelper::applyFiltering($query, $filters);

        // Get Total Count for Pagination
        $total = $query->count();



        // Apply Pagination
        $employee = PaginationHelper::applyPagination(
            $query->paginate($perPage)->items(),
            $perPage,
            $request->input('page', 1), // Default to page 1
            $total
        );

        // Return the data as a JSON response
        return response()->json([
            'data' => $employee->toArray(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $employee->currentPage(),
            'last_page' => $employee->lastPage(),
        ], 200);
    }


    public function getEmployee(string $employee_id)
    {
        // Find the specific resource
        $employee = Employee::find($employee_id); // Use the correct model name
        if (!$employee) {
            return response()->json(['message' =>  'employee not found'], 404); // Handle not found cases
        }


        return response()->json([$employee]);
    }

    public function updateEmployee(Request $request, string $employee_id)
    {
        // Update the resource
        $employee = Employee::find($employee_id); // Use the correct model name
        if (!$employee) {
            return response()->json(['message' => 'employee not found'], 404); // Handle not found cases
        }

        $employee->update($request->all());

        return response()->json([
            'message' => 'Successfully updated',
            'employee' => $employee // Return the updated book reservation data
        ], 200);
    }

    public function destroyEmployee(string $employee_id)
    {
        // Delete the resource
        $employee = Employee::find($employee_id); // Use the correct model name
        if (!$employee) {
            return response()->json(['message' => 'employee not found'], 404); // Handle not found cases
        }
        $employee->delete();
        return response()->json([
            'message' => 'Successfully deleted'
        ], 200);
    }
}
