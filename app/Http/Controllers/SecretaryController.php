<?php

namespace App\Http\Controllers;

use App\Models\Secretary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class SecretaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $secretaries = Secretary::with('user')->get();

        return response()->json([
            "success" => true,
            "message" => "Secretary List",
            "secretaries" => $secretaries
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
                "message" => "Check the ID.",
            ]);
        }

        $secretary = Secretary::with('user')->where('user_id', $request->user_id)->first();

        if (is_null($secretary)) {
            return response()->json([
                "success" => false,
                "message" => "Secretary Not Found.",
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Secretary retrieved successfully.",
            "secretary" => $secretary,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|numeric',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'department_id' => 'required|numeric',
            'phone_num' => 'required|numeric|min:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ]);
        }

        $user = User::find($request->user_id);
        $secretary = Secretary::where('user_id', $request->user_id)->first();

        if (!$user || !$secretary) {
            return response()->json([
                "success" => false,
                "message" => "Secretary Not Found.",
            ]);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->phone_num = $request->phone_num;
        $user->role = 'Secretary';
        $user->save();

        $secretary->department_id = $request->department_id;
        $secretary->save();

        return response()->json([
            "success" => true,
            "message" => "Secretary updated successfully.",
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
            ]);
        }

        $user = User::find($request->user_id);
        $secretary = Secretary::where('user_id', $request->user_id)->first();

        if (!$user || !$secretary) {
            return response()->json([
                "success" => false,
                "message" => "Secretary Not Found.",
            ]);
        }

        $secretary->delete();
        $user->delete();

        return response()->json([
            "success" => true,
            "message" => "Secretary deleted successfully.",
        ]);
    }
}
