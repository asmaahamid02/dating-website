<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ResponseJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use ResponseJson;

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {
        //validate the input data
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:20',
            'gender' => 'required',
            'interested_in' => 'required',
            'country' => 'required',
            'city' => 'required',
        ]);

        //return the validator errors
        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'data', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'interested_in' => $request->interested_in,
            'country' => $request->country,
            'city' => $request->city,
        ]);

        //return user data and success message
        return $this->jsonResponse($user, 'data', Response::HTTP_OK, 'Account Registered Successfully');
    }

    public function login(Request $request)
    {
        //validate the input data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:20',
        ]);

        //return the validator errors
        if ($validator->fails()) {
            return $this->jsonResponse($validator->errors(), 'data', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if (!$token = auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->jsonResponse(null, 'data', Response::HTTP_UNAUTHORIZED, 'Email/Password is wrong!');
        }
        $cookie = cookie('jwt', $token, 60 * 24); //one day
        return $this->jsonResponse(auth()->user(), 'data', Response::HTTP_OK)->withCookie($cookie);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');
        auth()->logout();
        return $this->jsonResponse('Logging Out', 'data', Response::HTTP_OK, 'Logged Out Successfully')->withCookie($cookie);
    }
}