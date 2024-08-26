<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMidleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// API Route
Route::post('/user-registration', [UserController::class,'UserRegistration']);
Route::post('/user-login', [UserController::class,'UserLogin']);
Route::post('/send-otp', [UserController::class,'SendOtpCode']);
Route::post('/verify-otp', [UserController::class,'VerifyOTP']);
// Token Verification
Route::post('/reset-password', [UserController::class,'ResetPassword'])->middleware([TokenVerificationMidleware::class]);

// Page Route

Route::get('/',[HomeController::class,'HomePage']);
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtppage',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtpage',[UserController::class,'VerifyOtpPage']);
Route::get('/resetPasswordpage',[UserController::class,'ResetPasswordPage']);
Route::get('/dashboardpage',[UserController::class,'DashboardPage']);




