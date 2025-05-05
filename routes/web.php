<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Form;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\FormFieldController;
use App\Http\Controllers\Admin\FormAnswerController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes - all protected by auth middleware
Route::prefix('admin')->middleware('auth')->as('admin.')->group(function() {
    Route::resource('forms', FormController::class);
    Route::resource('forms.fields', FormFieldController::class);
    Route::get('forms/{form}/answers', [FormAnswerController::class, 'index'])->name('forms.answers.index');
    Route::resource('form-answers', FormAnswerController::class)->except(['create', 'edit', 'update', 'store']);
});

Route::get('/forms/{id}', [FormController::class, 'showPublic'])->name('forms.public');
Route::post('/forms/{id}', [FormAnswerController::class, 'store'])->name('forms.submit');
Route::get('/forms/thankyou', function() { 
    return view('forms.thankyou'); 
})->name('forms.thankyou');

require __DIR__.'/auth.php';
