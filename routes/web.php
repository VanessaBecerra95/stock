<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckLogin;


Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $email = $request->input('email');
    $password = $request->input('password');

    if (!empty($email) && !empty($password)) {
        session(['logged_in' => true]);
        session(['user_email' => $email]);
        session(['password' => $password]);
        return redirect()->route('products.index')->with('success', '¡Bienvenido! Has iniciado sesión correctamente.');
    }

    return back()->withErrors(['message' => 'Por favor, ingrese un correo y contraseña.']);
})->name('login.post');

Route::get('/logout', function () {
    session()->forget(['logged_in', 'user_email']);
    return redirect()->route('login')->with('success', 'Has cerrado sesión correctamente.');
})->name('logout');


Route::middleware([CheckLogin::class])->group(function () {

    Route::get('/', [ProductController::class, 'index'])->name('products.index');

    Route::get('/create', [ProductController::class, 'create'])->name('products.create');

    Route::post('/store', [ProductController::class, 'store'])->name('products.store');

    Route::get('/update-branch', [ProductController::class, 'formBranch'])->name('products.formBranch');

    Route::post('/update-branch', [ProductController::class, 'updateBranch'])->name('products.updateBranch');

    Route::get('/search', function () {
        return view('products.search');
    })->name('products.search');

    Route::post('/search-results', [ProductController::class, 'search'])->name('products.searchResults');

    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');

    Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::get('/products/{id}/delete', [ProductController::class, 'confirmDelete'])->name('products.confirm-delete');

    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
