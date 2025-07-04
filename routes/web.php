<?php


use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('contacts', ContactController::class)->only([
    'index',
    'create',
    'store',
    'update',
    'destroy'
]);
