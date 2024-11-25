<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

Route::get('/auth/redirect', function () {
    return Socialite::driver('keycloak')->redirect();
})->name('auth.redirect');
Route::get("/login", function () {
    return redirect("/auth/redirect");
})->name("login");

Route::get('/auth/callback', function () {
    $user = Socialite::driver('keycloak')->user();

    $authUser = \App\Models\User::firstOrCreate(
        ['email' => $user->getEmail()],
        ['name' => $user->getName()],
    );

    auth()->login($authUser);

    return redirect('/dashboard');
})->name('auth.callback');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('welcome');
    });
});
