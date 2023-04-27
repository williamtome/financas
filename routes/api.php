<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')
    ->get('/', function (Request $request) {
        dd('Usuário autenticado:',$request->user());
    });

Route::post('/user', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'password' => 'required|string|min:8',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    return [
        'token' => $user->createToken($request->password)
                        ->plainTextToken,
    ];
});
