<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/produtcs/register',[ProductController::class, 'showForm']);
Route::post('/produtcs/register',[ProductController::class, 'productRegister']);
