<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidMail;
use Carbon\Carbon;
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
$request->birthdate = date('YYYY-MM-DD', '2021202120212021');
        return $request->birthdate;
        //specify your custom message here
        $messages = [
            'required' => ['error' => 'blank'],
            'numeric' => ['error' => "not_a_number"],
            'phone_number.regex' => ['error' => "invalid"],
            'in' => ['error' => 'inclusion'],
            'phone_number.min' => ['string' => ['error' => "too_short", 'count' => ':min']],
            'phone_number.max' => ['string' => ['error' => "too_long", 'count' => ':max']],
            'birthdate.date_format' => ['error' => "invalid"],
            'birthdate.before' => ['error' => "in_the_future"],
            'avatar.mimes' => ['error' => "invalid_content_type"],
            'unique' => ['error' => "taken"],
        ];
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:75',
            'last_name' => 'required|string|max:75',
            'country_code' => [Rule::in(['EG'])],
            'phone_number' => 'required|unique:users,phone_number|min:10|max:15|regex:/(01)[0-9]{9}/',
            'gender' => [Rule::in(['male', 'female'])],
            'birthdate' => 'required|string|max:75|date_format:YYYY-MM-DD|before:today',
            'avatar' => 'required|file|mimes:jpeg,png,jpg,gif',
            'email' => ['required','unique:users,email', new ValidMail],
            'password' => 'required|string|confirmed|min:6',
        ], $messages);

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
