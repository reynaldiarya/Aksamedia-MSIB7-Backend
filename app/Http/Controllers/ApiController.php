<?php

namespace App\Http\Controllers;

use App\Http\Resources\DivisionCollection;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\LoginResource;
use App\Models\Division;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'The provided credentials are incorrect.',
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => new LoginResource($user),
        ], 200);
    }

    public function apiGetAllDataDivision(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $division = Division::where('name', 'LIKE', '%' . $request->name . '%')->paginate();

        return response()->json([
            'status' => 'success',
            'message' => 'Success retrieving data',
            'data' => new DivisionCollection($division),
            'pagination' => [
                'meta' => [
                    'current_page' => $division->currentPage(),
                    'from' => $division->firstItem(),
                    'last_page' => $division->lastPage(),
                    'path' => $division->path(),
                    'per_page' => $division->perPage(),
                    'to' => $division->lastItem(),
                    'total' => $division->total(),
                ],
                'links' => [
                    'first' => $division->url(1),
                    'last' => $division->url($division->lastPage()),
                    'self' => $division->url($division->currentPage()),
                    'next' => $division->nextPageUrl(),
                    'prev' => $division->previousPageUrl()
                ],
            ]
        ], 200);
    }

    public function apiGetAllDataKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'division_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $employee = Employee::with('division')->where('name', 'LIKE', '%' . $request->name . '%')->where('division_id', 'LIKE', $request->division_id)->paginate();

        return response()->json([
            'status' => 'success',
            'message' => 'Success retrieving data',
            'data' => new EmployeeCollection($employee),
            'pagination' => [
                'meta' => [
                    'current_page' => $employee->currentPage(),
                    'from' => $employee->firstItem(),
                    'last_page' => $employee->lastPage(),
                    'path' => $employee->path(),
                    'per_page' => $employee->perPage(),
                    'to' => $employee->lastItem(),
                    'total' => $employee->total(),
                ],
                'links' => [
                    'first' => $employee->url(1),
                    'last' => $employee->url($employee->lastPage()),
                    'self' => $employee->url($employee->currentPage()),
                    'next' => $employee->nextPageUrl(),
                    'prev' => $employee->previousPageUrl()
                ],
            ]
        ], 200);
    }

    public function apiCreateDataKaryawan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'name' => 'required|string',
            'phone' => 'required|numeric|digits_between:7,15|unique:employees',
            'division' => 'required|string',
            'position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $employee = new Employee();
            $employee->name = $request->input('name');
            $employee->phone = $request->input('phone');
            $employee->division_id = $request->input('division');
            $employee->position = $request->input('position');
            $originname = $request->image->getClientOriginalName();
            $filename = pathinfo($originname, PATHINFO_FILENAME);
            $extension = $request->image->getClientOriginalExtension();
            $filename = $filename . '_' . time() . '.' . $extension;
            Storage::disk('local')->put('public/profile/' . $filename, $request->image->getContent());
            $employee->image = $filename;
            $employee->save();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully added data',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function apiUpdateDataKaryawan(Request $request, $uuid)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'name' => 'required|string',
            'phone' => 'required|numeric|digits_between:7,15|unique:employees',
            'division' => 'required|string',
            'position' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        DB::beginTransaction();
        try {
            $employee = Employee::where('id', $uuid)->first();
            if ($employee) {
                $employee->name = $request->input('name');
                $employee->phone = $request->input('phone');
                $employee->division_id = $request->input('division');
                $employee->position = $request->input('position');
                if ($request->image) {
                    $originname = $request->image->getClientOriginalName();
                    $filename = pathinfo($originname, PATHINFO_FILENAME);
                    $extension = $request->image->getClientOriginalExtension();
                    $filename = $filename . '_' . time() . '.' . $extension;
                    Storage::disk('local')->put('public/profile/' . $filename, $request->image->getContent());
                    $employee->image = $filename;
                }
                $employee->save();
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully updated data',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found',
                ], 400);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function apiDeleteDataKaryawan($uuid)
    {
        if ($uuid) {
            $employee = Employee::where('id', $uuid)->first();
            if ($employee) {
                $employee->delete();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Successfully deleted data',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee not found',
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Id not found',
            ], 400);
        }
    }

    public function apiLogout(Request $request)
    {
        $message = $request->user()->currentAccessToken()->delete();
        if ($message == 1) {
            return response()->json([
                'status' => 'success',
                'message' => 'Logout Successful'
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Logout Unsuccessful'
            ], 400);
        }
    }
}
