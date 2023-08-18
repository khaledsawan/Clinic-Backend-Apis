<?php

namespace App\Http\Controllers;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class AppointmentController extends Controller
{

    public function sendFCMNotification($userId)
    {
        $user = Patient::where('user_id',$userId)->first();
        $deviceToken = $user->FCMToken;
        $SERVER_API_KEY = env('FCM_KEY');
        $data = [
            "to" => $deviceToken,
            "priority"=>'high',
            "notification" => [
                "title" => 'Clinic',
                "body" => 'pls appointment in app',
            ]
        ];
        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, env('FCM_URL'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_exec($ch);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Appointment::get();
        return response()->json([
            "success" => true,
            "message" => "Appointment List",
            "Appointment" => $products
        ]);
    }

    //secrtarea
    public function indexByDepartment(Request $request)
    {
        $products = Appointment::where('department_id', $request->id)->get();
        return response()->json([
            "success" => true,
            "message" => "Appointment List",
            "Appointment" => $products
        ]);
    }

    //doctor
    public function indexByDoctor(Request $request)
    {
        $products = Appointment::where('doctor_id', $request->id)->get();
        return response()->json([
            "success" => true,
            "message" => "Appointment List",
            "Appointment" => $products
        ]);
    }


    //create appointment ferom patient
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'time' => 'required|string',
            'description' => 'required|string',
            'department_id' => 'required|string',

        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = Auth::user();

        $product = new Appointment();
        $product->date = $request->date;
        $product->time = $request->time;
        $product->description = $request->description;
        $product->department_id = $request->department_id;
        $product->patient_id = $user->id;
        $product->status = 'waiting';
        $product->save();
        return response()->json([
            "success" => true,
            "message" => "Appointment created successfully.",
            "Appointment" => $product
        ]);
    }
    // edit Appointment
    public function AppointmentHandle(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|string',
            'time' => 'required|string',
            'status' => 'required|string',
            //  'doctor_id' => 'required',
            'reason' => 'required|string',
            'id' => 'required',

        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = Auth::user();
        $product =  Appointment::find($request->id);
        $product->date = $request->date;
        $product->time = $request->time;
        $product->status = $request->status;
        //$product->doctor_id=$request->doctor_id;
        $product->cancel_reason = $request->reason;
        $product->save();

        $this->sendFCMNotification($product->patient_id);
        return response()->json([
            "success" => true,
            "message" => "Appointment updated successfully.",
            "Appointment" => $product
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
            'id' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $product =  Appointment::find($request->id);
        $product->name = $request->name;
        $product->save();
        return response()->json([
            "success" => true,
            "message" => "Appointment updated successfully.",
            "Appointment" => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $ad
     * @return \Illuminate\Http\Response
     */
    public function view(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "chake the id.",
            ]);
        }
        $product = Appointment::find($request->id);
        if (is_null($product)) {
            return response()->json([
                "success" => false,
                "message" => "Product Not Find.",
            ]);
        }
        return response()->json([
            "success" => true,
            "message" => "Appointment retrieved successfully.",
            "item" => $product
        ]);
    }

    public function delete(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Appointment::find($request->id);
        $product->delete();

        return response()->json([
            "success" => true,
            "message" => "Appointment deleted successfully.",
        ]);
    }
}
