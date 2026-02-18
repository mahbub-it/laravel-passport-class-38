<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
    ]);

    $user = new User([
        'name' => $request->name,
        'email' => $request->email,
        'password' => $request->password,
    ]);

    if ($user->save()) {
        return response()->json([
            'message' => 'User registration successfully',
            'user' => $user,
        ]);
    } else {
        return response()->json([
            'error' => 'User registration failed',
        ]);
    }
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {

        $user = auth()->user();

        $token = $user->createToken('token')->accessToken;

        return $token;
    }

    return response()->json(['message' => 'Unauthorized'], 401);
});

Route::get('/profile', function () {

    return 'My Profile';

})->middleware('auth:api');
