<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Mockery\Expectation;

class UserController extends Controller
{
    function UserRegistration(Request $request){
        try{
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password')
            ]);

            return response()->json([
                "status"=>"Success",
                "message"=>"User Registration Successfully"
            ],200);

        }catch(Exception $e){
            return response()->json([
                "status"=>"Failed",
                "message"=>$e->getMessage()
            ],200);
        }

    }
    function UserLogin(Request $request){
        $count = User::where('email',$request->input('email'))
         ->where('password',$request->input('password'))->count();
         if($count==1){
             // user Login-> JWT Token issue
             $token = JWTToken::CreateToken($request->input('email'));
             return response()->json([
                 "status"=>"success",
                 "message"=>"User Login successfully",

             ],200)->cookie('token',$token,60*24*30,'/');
         }else{
             return response()->json([
                 "status"=>"Failed",
                 "message"=>"unauthorized"
             ],404);
         }

            }


    function SendOtpCode(Request $request){
                $email = $request->input('email');
                $otp = rand(1000,9999);
                $count = User::where('email',$request->input('email','=',$email))->count();

            if($count==1){
                //  OTP email address
                Mail::to($email)->send(new OtpMail($otp));
                // OTP table update
                User::where('email',$request->input('email','=',$email))->update(['otp'=>$otp]);
                return response()->json([
                    "status"=>"Success",
                    "message"=>"4 Digit OTP code has been send to your"
                ],200);
            }else{
                return response()->json([
                    "status"=>"Failed",
                    "message"=>"unauthorized"
                ],200);
            }
            }

    function VerifyOTP(Request $request){
                $email = $request->input('email');
                $otp = $request->input('otp');
                $count=User::where('email','=',$email)
                ->where('otp','=',$otp)->count();
                if($count==1){

                    //Database OTP Update
                    User::where('email',$request->input('email','=',$email))->update(['otp'=>'0']);
                    // Pass Reset Token Issue
                    $token = JWTToken::CreateTokenForForgetPassword($request->input('email'));
                    return response()->json([
                        "status"=>"Success",
                        "message"=>"OTP Verification Successfully",
                        "token"=>$token
                    ],200);
                }else{
                    return response()->json([
                        "status"=>"Failed",
                        "message"=>"OTP Verification Failed"
                    ],401);
                }

            }
    function ResetPassword(Request $request){

        try{
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email','=',$email)->update(['password'=>$password]);
           return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
        ],200);
        }catch(Expectation $e){
            return response()->json([
                "status"=>"Failed",
                "message"=>"Reset PassWord Failed"
            ],401);
        }

    }
// Page Route

function LoginPage(){
return view('pages.auth.login-page');
}

function RegistrationPage(){
return view('pages.auth.registration-pages');
}

function SendOtpPage(){
return view('pages.auth.send-otp-page');
}

function VerifyOtpPage(){

}

function ResetPasswordPage(){

}

function DashboardPage(){

}


}

