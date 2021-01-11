<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Validator;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT token via given credentials.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials)) {
            return $this->respondWithToken($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Log the user out (Invalidate the token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {

        //specify your custom message here
        $messages = [
            'required' => ['error' => 'blank'],
            'numeric' => ['error' => "not_a_number"],
            'string' => 'The :attribute must be text format',
            'file' => 'The :attribute must be a file',
            'mimes' => 'Supported file format for :attribute are :mimes',
            'max' => 'The :attribute must have a maximum length of :max',
            'country_code.in' =>  ['error' =>'inclusion'],
            'regex' => 'inclusion',
        ];
        $request->merge([
            'country_code' => 'EG',
            'phone_number' => 'EG',
        ]);
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:75',
            'last_name' => 'required|string|max:75',
            'country_code' => ['required', Rule::in(['EG'])],
            'phone_number' => 'required|numeric|regex:/(01)[0-9]{9}/',
            'gender' => 'required|string|max:75',
            'birthdate' => 'required|string|max:75',
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif',
            'email' => 'required|string',
            'password' => 'required|string|confirmed|min:6',
        ], $messages);


//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string|between:2,100',
//            'email' => 'required|string|email|max:100|unique:users',
//            'password' => 'required|string|confirmed|min:6',
//        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->toJson()], 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

}
