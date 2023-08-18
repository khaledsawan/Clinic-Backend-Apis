<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patients = Patient::with('user')->get();

        return response()->json([
            "success" => true,
            "message" => "Patient List",
            "patients" => $patients
        ]);
    }

    public function view(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $patient = Patient::where('user_id', $request->user_id)->with('user')->first();

        if (is_null($patient)) {
            return response()->json([
                "success" => false,
                "message" => "Patient Not Found.",
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Patient retrieved successfully.",
            "patient" => $patient,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'string|max:20',
            'address' => 'required|string|max:100',
            'birth_date' => 'required|string|max:40',
            'phone_num' => 'required|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $patient = Patient::where('user_id', $request->user_id)->with('user')->first();

        if (is_null($patient)) {
            return response()->json([
                "success" => false,
                "message" => "Patient Not Found.",
            ]);
        }

        $patient->gender = $request->gender;
        $patient->address = $request->address;
        $patient->user->phone_num = $request->phone_num;
        $patient->user->first_name = $request->first_name;
        $patient->user->last_name = $request->last_name;
        $patient->user->save();
        $patient->save();

        return response()->json([
            "success" => true,
            "message" => "Patient updated successfully.",
            "patient" => $patient,
        ]);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $patient = Patient::where('user_id', $request->user_id)->with('user')->first();

        if (is_null($patient)) {
            return response()->json([
                "success" => false,
                "message" => "Patient Not Found.",
            ]);
        }

        $patient->delete();
        $patient->user->delete();

        return response()->json([
            "success" => true,
            "message" => "Patient deleted successfully.",
        ]);
    }
}
