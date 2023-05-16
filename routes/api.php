<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/users', [\App\Http\Controllers\Api\UserController::class, 'index']);
