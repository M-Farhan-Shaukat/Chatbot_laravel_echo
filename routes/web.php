<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

// Root — show login
Route::get('/', fn() => view('auth.login'));

// Auth
Route::post('/login', [AuthController::class, 'checkPhone']);
Route::get('/register', fn() => redirect('/'));
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout']);

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/app', [ChatController::class, 'app']);
    Route::get('/chat/{userId}', [ChatController::class, 'open']);
    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::post('/chat/mark-read/{conversationId}', [ChatController::class, 'markRead']);
    Route::get('/chat-data/{userId}', [ChatController::class, 'chatData']);

    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/add', [ContactController::class, 'create']);
    Route::post('/contacts/add', [ContactController::class, 'store']);

    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::post('/profile', [ProfileController::class, 'update']);
});
