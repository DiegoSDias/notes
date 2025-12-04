<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\CheckIsAdmin;
use App\Http\Middleware\CheckIsLogged;
use App\Http\Middleware\CheckIsNotLogged;
use Illuminate\Support\Facades\Route;

// auth routes - user not logged
Route::middleware([CheckIsNotLogged::class])->group(function(){
    Route::get("/login", [AuthController::class, 'login'])->name('login');
    Route::post('/loginSubmit', [AuthController::class, 'loginSubmit']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/registerSubmit', [AuthController::class, 'store'])->name('registerSubmit');
});

// app routes - user logged
Route::middleware([CheckIsLogged::class])->group(function() {
    Route::get("/", [MainController::class, 'index'])->name('home');
    Route::get("/newNote", [MainController::class, 'newNote'])->name('newNote');
    Route::post("/newNoteSubmit", [MainController::class, 'newNoteSubmit'])->name('newNoteSubmit');

    //edit note
    Route::get("/editNote/{id}", [MainController::class, 'editNote'])->name('edit');
    Route::post("/editNoteSubmit", [MainController::class, 'editNoteSubmit'])->name('editNoteSubmit');
    
    //delete note
    Route::delete("/deleteNote/{id}", [MainController::class, 'deleteNote'])->name('delete');

    Route::get("/logout", [AuthController::class, 'logout'])->name('logout');

    Route::get("/community", [MainController::class, 'community'])->name('community');

    //edit user
    Route::resource('user', AuthController::class);
});


Route::prefix('admin')
    ->name('admin.')
    ->middleware([CheckIsLogged::class, CheckIsAdmin::class])
    ->group(function(){
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('users', AdminController::class);
        Route::get('/community-notes', [AdminController::class, 'communityNotes'])->name('communityNotes');
        Route::resource('notes', AdminController::class);
});
