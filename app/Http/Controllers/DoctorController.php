<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doctors = Doctor::with('user')->get();

        return response()->json([
            "success" => true,
            "message" => "Doctor List",
            "doctors" => $doctors
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

        $doctor = Doctor::with('user')->where('user_id', $request->user_id)->first();

        if (is_null($doctor)) {
            return response()->json([
                "success" => false,
                "message" => "Doctor Not Found.",
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Doctor retrieved successfully.",
            "doctor" => $doctor
        ]);
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'specialty' => 'string|max:80',
            'department_id' => 'required|numeric',
            'consultation_price' => 'required|numeric',
            'phone_num' => 'required|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 422);
        }

        $doctor = Doctor::where('user_id', $request->user_id)->first();
        $user = User::find($request->user_id);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_num = $request->phone_num;
        $user->role = 'doctor';
        $user->save();

        if ($request->hasFile('img')) {
            $file = $request->file('img');
            $destinationPath = public_path('uploads');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $fileName);
            $doctor->image_path = $destinationPath . '/' . $fileName;
        }

        $doctor->review = 1;
        $doctor->specialty = $request->specialty;
        $doctor->department_id = $request->department_id;
        $doctor->consultation_price = $request->consultation_price;
        $doctor->save();

        return response()->json([
            "success" => true,
            "message" => "Doctor Updated successfully.",
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

        $user = User::find($request->user_id);
        $doctor = Doctor::where('user_id', $request->user_id)->first();

        if ($doctor) {
            $doctor->delete();
        }

        if ($user) {
            $user->delete();
        }

        return response()->json([
            "success" => true,
            "message" => "Doctor deleted successfully.",
        ]);
    }
}
