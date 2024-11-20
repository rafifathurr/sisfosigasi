<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    public function login()
    {
        return view('auth.login');
    }
    public function auth(Request $request)
    {

        $response = (new ApiAuthController)->authenticate($request);

        $response_body = json_decode($response->content());

        if ($response_body->status == 200) {

            session(['jwt_token' => $response_body->data->token]);
            session(['name' => $response_body->data->user->name]);
            session(['id' => $response_body->data->user->id]);

            return redirect()->route('view-dashboard');
        }

        return redirect()->route('view-login')->withErrors($response_body->message)->withInput();
    }

    public function logout()
    {

        session()->forget('jwt_token');

        return redirect()->route('view-login');
    }
}
