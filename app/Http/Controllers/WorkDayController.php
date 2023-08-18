<?php

namespace App\Http\Controllers;

use App\Models\WorkDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class WorkDayController extends Controller
{

    public function index()
    {
        $workDays = WorkDay::get();
        return response()->json([
            "success" => true,
            "message" => "WorkDay List",
            "data" => $workDays
        ]);
    }

    public function indexMyTime()
    {
        $user = Auth::user();
        $workDays = WorkDay::where('doctor_id', $user->id)->get();
        return response()->json([
            "success" => true,
            "message" => "My WorkDay List",
            "workDay" => $workDays
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ]);
        }

        $workDay = new WorkDay();
        $workDay->day = $request->day;
        $workDay->start_time = $request->start_time;
        $workDay->end_time = $request->end_time;
        $workDay->doctor_id = $request->user_id;
        $workDay->save();

        return response()->json([
            "success" => true,
            "message" => "WorkDay created successfully.",
            "data" => $workDay
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'day' => 'required|string',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'user_id' => 'required',
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ]);
        }

        $user = Auth::user();
        if ($user->role == 'admin' || $user->user_id) {
            $workDay = WorkDay::find($request->id);
            $workDay->day = $request->day;
            $workDay->start_time = $request->start_time;
            $workDay->end_time = $request->end_time;
            $workDay->doctor_id = $request->user_id;
            $workDay->save();

            return response()->json([
                "success" => true,
                "message" => "WorkDay updated successfully.",
                "data" => $workDay
            ]);
        }

        return response()->json([
            "success" => false,
            "message" => "Not authorized.",
        ], 401);
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Check the ID.",
            ]);
        }

        $workDay = WorkDay::find($request->id);

        if (is_null($workDay)) {
            return response()->json([
                "success" => false,
                "message" => "WorkDay Not Found.",
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "WorkDay retrieved successfully.",
            "item" => $workDay
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
            ]);
        }

        $workDay = WorkDay::find($request->id);
        $workDay->delete();

        return response()->json([
            "success" => true,
            "message" => "WorkDay deleted successfully.",
        ]);
    }
}
