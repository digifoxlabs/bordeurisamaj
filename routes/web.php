<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDocumentController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MemberController::class, 'home'])->name('home');
Route::get('/membership', [MemberController::class, 'create'])->name('membership.create');
Route::post('/membership/edit', [MemberController::class, 'editDraft'])->name('membership.edit-draft');
Route::post('/membership/preview', [MemberController::class, 'preview'])->name('membership.preview');
Route::post('/membership', [MemberController::class, 'store'])->name('membership.store');
Route::post('/membership/check-mobile', [MemberController::class, 'checkMobile'])->name('membership.check-mobile');
Route::get('/success/{member}', [MemberController::class, 'success'])->name('success');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'login'])->name('login');
    Route::post('/login', [AdminController::class, 'authenticate'])->name('authenticate');
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/members/{member}', [AdminController::class, 'show'])->name('members.show');
        Route::get('/members/{member}/edit', [AdminController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [AdminController::class, 'update'])->name('members.update');
        Route::get('/members/{member}/documents/{document}/download', [AdminController::class, 'downloadMemberDocument'])->name('members.documents.download');
        Route::delete('/members/{member}/documents/{document}', [AdminController::class, 'deleteMemberDocument'])->name('members.documents.destroy');
        Route::delete('/members/{member}', [AdminController::class, 'destroy'])->name('members.destroy');
        Route::get('/profile', [AdminController::class, 'profile'])->name('profile');
        Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');
        Route::get('/documents', [AdminDocumentController::class, 'index'])->name('documents.index');
        Route::post('/documents', [AdminDocumentController::class, 'store'])->name('documents.store');
        Route::get('/documents/{document}/download', [AdminDocumentController::class, 'download'])->name('documents.download');
        Route::delete('/documents/{document}', [AdminDocumentController::class, 'destroy'])->name('documents.destroy');
    });
});
