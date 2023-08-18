<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SecretaryController;
use App\Http\Controllers\WorkDayController;
use App\Http\Controllers\AppointmentController;



Route::post('registerAdmin', [ApiAuthController::class, 'registerAdmin']);
Route::post('registerPatient', [ApiAuthController::class, 'registerPatient']);

Route::post('ActivateEmail', [ApiAuthController::class, 'ActivateEmail']); //notify email
Route::post('create_password', [ApiAuthController::class, 'create_password'])->name('create_password');
Route::post('resend_code', [ApiAuthController::class, 'resend_code'])->name('resend_code');

Route::post('forgot_password', [ApiAuthController::class, 'forgot_password']);
Route::post('login', [ApiAuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('logout', [ApiAuthController::class, 'logout']);
    Route::get('indexAppointment', [AppointmentController::class, 'index']);
    Route::post('viewAppointment', [AppointmentController::class, 'view']);

    Route::middleware('admin')->group(function () {
        Route::post('registerSecretary', [ApiAuthController::class, 'registerSecretary']);
        Route::post('registerDoctor', [ApiAuthController::class, 'registerDoctor']);

        Route::get('indexDoctor', [DoctorController::class, 'index']);
        Route::post('updateDoctor', [DoctorController::class, 'update']);
        Route::post('viewDoctor', [DoctorController::class, 'view']);
        Route::post('deleteDoctor', [DoctorController::class, 'delete']);

        Route::get('indexSecretary', [SecretaryController::class, 'index']);
        Route::post('updateSecretary', [SecretaryController::class, 'update']);
        Route::post('viewSecretary', [SecretaryController::class, 'view']);
        Route::post('deleteSecretary', [SecretaryController::class, 'delete']);

        Route::get('indexPatient', [PatientController::class, 'index']);
        Route::post('updatePatient', [PatientController::class, 'update']);
        Route::post('viewPatient', [PatientController::class, 'view']);
        Route::post('deletePatient', [PatientController::class, 'delete']);

        Route::get('indexDepartment', [DepartmentController::class, 'index']);
        Route::post('storeDepartment', [DepartmentController::class, 'store']);
        Route::post('updateDepartment', [DepartmentController::class, 'update']);
        Route::post('viewDepartment', [DepartmentController::class, 'view']);
        Route::post('deleteDepartment', [DepartmentController::class, 'delete']);


        Route::get('indexWorkDay', [WorkDayController::class, 'index']);
        Route::post('storeWorkDay', [WorkDayController::class, 'store']);
        Route::post('updateWorkDay', [WorkDayController::class, 'update']);
        Route::post('deleteWorkDay', [WorkDayController::class, 'delete']);
        Route::post('viewWorkDay', [WorkDayController::class, 'view']);


        //Route::post('updateAppointment', [AppointmentController::class, 'update']);




    });

    Route::middleware('patient')->group(function () {

        Route::post('storeAppointment', [AppointmentController::class, 'store']);
        Route::post('deleteAppointment', [AppointmentController::class, 'delete']);

    });

    Route::middleware('doctor')->group(function () {


        Route::get('indexMyTime', [WorkDayController::class, 'indexMyTime']);


    });
    Route::middleware('secretary')->group(function () {

    Route::post('AppointmentHandle', [AppointmentController::class, 'AppointmentHandle']);


    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
