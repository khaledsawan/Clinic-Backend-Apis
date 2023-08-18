<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = Department::get();

        return response()->json([
            "success" => true,
            "message" => "Department List",
            "departments" => $departments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $department = new Department();
        $department->name = $request->name;
        $department->save();

        return response()->json([
            "success" => true,
            "message" => "Department created successfully.",
            "department" => $department
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $department = Department::find($request->id);

        if (is_null($department)) {
            return response()->json([
                "success" => false,
                "message" => "Department Not Found.",
            ]);
        }

        $department->name = $request->name;
        $department->save();

        return response()->json([
            "success" => true,
            "message" => "Department updated successfully.",
            "department" => $department
        ]);
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $department = Department::find($request->id);

        if (is_null($department)) {
            return response()->json([
                "success" => false,
                "message" => "Department Not Found.",
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Department retrieved successfully.",
            "department" => $department
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $department = Department::find($request->id);

        if (is_null($department)) {
            return response()->json([
                "success" => false,
                "message" => "Department Not Found.",
            ]);
        }

        $department->delete();

        return response()->json([
            "success" => true,
            "message" => "Department deleted successfully.",
        ]);
    }
}
