<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
class StateController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['only' => ['getState']]);
    }
    public function getState(Request $request)
    {
        $user = Auth::user();
        if ($request->phone_number == $user->phone_number)
            return Auth::user();
        return Response::json(array('error' => 'invalid data'), 200);

    }
}
