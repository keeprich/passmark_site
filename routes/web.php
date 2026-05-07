<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PracticeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('/about-us', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/blog', 'pages.blog')->name('blog');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/practice', [PracticeController::class, 'index'])->name('practice.index');
    Route::post('/practice/submit', [PracticeController::class, 'submit'])->name('practice.submit');
    Route::post('/practice/upgrade', [PracticeController::class, 'activatePremium'])->name('practice.upgrade');
    Route::post('/practice/ai-explain', [PracticeController::class, 'aiExplain'])->name('practice.ai-explain');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function (): void {
    Route::get('/dashboard', AdminDashboardController::class)->name('dashboard');
    Route::post('/questions/import', [AdminQuestionController::class, 'import'])->name('questions.import');
    Route::resource('questions', AdminQuestionController::class)->except('show');
});
