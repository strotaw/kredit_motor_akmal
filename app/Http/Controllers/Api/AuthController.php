<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\AuthController as WebAuthController;
use Illuminate\Http\Request;

class AuthController extends WebAuthController
{
    public function register(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        return parent::register($request);
    }

    public function login(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        return parent::login($request);
    }

    public function logout(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        return parent::logout($request);
    }

    public function me(Request $request)
    {
        return response()->json([
            'user' => $request->user()->loadMissing('profile'),
        ]);
    }
}
