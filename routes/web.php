<?php

use App\Http\Controllers\ProfileController;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DropboxController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('auth/dropbox', function () {
    return Socialite::driver('dropbox')->redirect();
})->name('connect.dropbox');

Route::get('auth/dropbox/callback', function () {
    $user = Socialite::driver('dropbox')->user();

    // Store the user's Dropbox token
    $authUser = Auth::user();
    $authUser->dropbox_token = $user->token;
    $authUser->save();

    return redirect()->route('dashboard')->with('success', 'Dropbox connected successfully');
});

Route::get('/dropbox/connect', [DropboxController::class, 'connect'])->name('dropbox.connect');
Route::get('/dropbox/callback', [DropboxController::class, 'callback'])->name('dropbox.callback');
Route::post('/dropbox/disconnect', [DropboxController::class, 'disconnect'])->name('disconnect.dropbox');
Route::post('upload-dropbox', [DropboxController::class, 'uploadFromDropbox'])->name('upload.dropbox');

Route::post('/upload/dropbox/files', [DropboxController::class, 'uploadDropboxFiles'])->name('upload.dropbox.files');

require __DIR__.'/auth.php';
