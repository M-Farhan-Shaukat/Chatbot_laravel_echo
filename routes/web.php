<?php

use App\Http\Controllers\AuthController;
//use Illuminate\Routing\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('/login', [AuthController::class, 'loginOrRegister']);

Route::get('/logout', [AuthController::class, 'logout']);


Route::get('/contacts', [ContactController::class, 'index'])->middleware('auth');

//Route::get('/chat/{userId}', [ChatController::class, 'open']);

Route::middleware('auth')->group(function () {
    Route::get('/app', [ChatController::class, 'app']);
    Route::get('/chat/{userId}', [ChatController::class, 'open']);

    Route::post('/chat/send', [ChatController::class, 'send']);
    Route::post('/chat/mark-read/{conversationId}', [ChatController::class, 'markRead']);
    
    Route::get('/chats', [ChatController::class, 'chats'])->middleware('auth');
    Route::get('/chat/{userId}', [ChatController::class, 'open']);
    Route::get('/chat-data/{userId}', [ChatController::class, 'chatData']);

    Route::get('/contacts', [ContactController::class, 'index']);
    Route::get('/contacts/add', [ContactController::class, 'create']);
    Route::post('/contacts/add', [ContactController::class, 'store']);
});

