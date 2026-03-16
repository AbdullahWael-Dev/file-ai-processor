<?php

use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OpenRouterChatController;

    Route::get('/', function() {
        return view('upload');
    })->name('file.actions');

    Route::post('/file-process', [OpenRouterChatController::class, 'process'])->name('file.process');
Route::get('login', [GoogleController::class, 'redirectToGoogle'])->name('login');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::post('/logout', [App\Http\Controllers\Auth\GoogleController::class, 'logout'])->name('logout');
Route::get('/download/pdf', action: [OpenRouterChatController::class, 'downloadPdf'])->name('download.pdf');
Route::get('/download/word', [OpenRouterChatController::class, 'downloadWord'])->name('download.word');
