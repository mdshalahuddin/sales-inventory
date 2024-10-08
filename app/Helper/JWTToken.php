<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\Key;

use Firebase\JWT\JWT;

class JWTToken{
  public static  function CreateToken($userEmail):string{
        $key = env('JWT_KEY');
        $payload =[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*60,
            'userEmail'=>$userEmail
        ];
        return JWT::encode($payload,$key,'HS256');
    }
    public static  function CreateTokenForForgetPassword($userEmail):string{
        $key = env('JWT_KEY');
        $payload =[
            'iss'=>'laravel-token',
            'iat'=>time(),
            'exp'=>time()+60*20,
            'userEmail'=>$userEmail
        ];
        return JWT::encode($payload,$key,'HS256');
    }

    public static  function VerifyToken($token):string{
        try{
        $key = env('JWT_KEY');
        $decode =  JWT::decode($token,new Key($key,'HS256'));
        return $decode->userEmail;
        }catch(Exception){

            return 'unauthorized';
        }

    }
}
