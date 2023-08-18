<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Models\Patient;
use App\Models\Secretary;
use App\Models\Doctor;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = $this->validateLoginRequest($request);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ['token' => $token, 'role' => $user->role];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }

    public function registerPatient(Request $request)
    {
        $validator = $this->validatePatientRegistrationRequest($request);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = new User();
        $user->fill($request->only(['first_name', 'last_name', 'email', 'phone_num']));
        $user->password = Hash::make($request->password);
        $user->role = 'patient';
        $user->save();

        $patient = new Patient();
        $patient->fill($request->only(['gender', 'address', 'birth_date']));
        $patient->user_id = $user->id;
        $patient->FCMToken = $request->FCMToken;
        $patient->save();

        $token = $user->createToken('235464576456')->accessToken;
        $response = ['token' => $token, 'role' => $user->role];
        return response($response, 200);
    }

    public function registerSecretary(Request $request)
    {
        $validator = $this->validateSecretaryRegistrationRequest($request);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = new User();
        $user->fill($request->only(['first_name', 'last_name', 'email', 'phone_num']));
        $user->password = Hash::make($request->password);
        $user->role = 'secretary';
        $user->save();

        $secretary = new Secretary();
        $secretary->department_id = $request->department_id;
        $secretary->user_id = $user->id;
        $secretary->save();

        $token = $user->createToken('235464576456')->accessToken;
        $response = ['token' => $token, 'role' => $user->role];
        return response($response, 200);
    }

    public function registerDoctor(Request $request)
    {
        $validator = $this->validateDoctorRegistrationRequest($request);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = new User();
        $user->fill($request->only(['first_name', 'last_name', 'email', 'phone_num']));
        $user->password = Hash::make($request->password);
        $user->role = 'doctor';
        $user->save();

        $doctor = new Doctor();
        $doctor->fill($request->only(['department_id', 'specialty']));
        $doctor->user_id = $user->id;
        $doctor->save();

        $token = $user->createToken('235464576456')->accessToken;
        $response = ['token' => $token, 'role' => $user->role];
        return response($response, 200);
    }

    private function validateLoginRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    private function validatePatientRegistrationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_num' => 'required|unique:users',
            'password' => 'required|min:6',
            'gender' => 'required',
            'address' => 'required',
            'birth_date' => 'required|date',
            'FCMToken' => 'required',
        ]);
    }

    private function validateSecretaryRegistrationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_num' => 'required|unique:users',
            'password' => 'required|min:6',
            'department_id' => 'required|exists:departments,id',
        ]);
    }

    private function validateDoctorRegistrationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_num' => 'required|unique:users',
            'password' => 'required|min:6',
            'department_id' => 'required|exists:departments,id',
            'specialty' => 'required',
        ]);
    }

    public function registerAdmin(Request $request)
    {
        $validator = $this->validateAdminRegistrationRequest($request);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = new User();
        $user->fill($request->only(['first_name', 'last_name', 'email', 'phone_num']));
        $user->password = Hash::make($request->password);
        $user->role = 'admin';
        $user->save();
        $admin = new Admin();
        $admin->fill($request->only(['first_name', 'last_name', 'email']));
        $admin->password = $request->password;
        $admin->functional_level = '1';
        $admin->user_id = $user->id;
        $admin->save();
        $token = $user->createToken('lkzdnfl9765kjdsgafk234jlk2345sLKasdasd546waed')->accessToken;
        $response = ['token' => $token, 'role' => $user->role];
        return response($response, 200);
    }

    public function logout()
    {
        Auth::user()->token()->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    private function validateAdminRegistrationRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_num' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    }

}
