<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GymPackageController as AdminGymPackageController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\RegistrationController as AdminRegistrationController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\TrainerController as AdminTrainerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SiteController::class, 'index'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [SiteController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-registrations', [SiteController::class, 'registrations'])->name('site.registrations');
    Route::get('/my-schedules', [SiteController::class, 'schedules'])->name('site.schedules');
    Route::get('/my-payments', [SiteController::class, 'payments'])->name('site.payments');
    Route::get('/packages/{gymPackage}/register', [RegistrationController::class, 'create'])->name('site.package.register.confirm');
    Route::post('/packages/{gymPackage}/register', [RegistrationController::class, 'store'])->name('site.package.register');
    Route::patch('/my-registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('site.registrations.cancel');
    Route::post('/my-registrations/{registration}/schedules', [RegistrationController::class, 'storeSchedule'])->name('site.registrations.schedule');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('site.reviews.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('members/create', [AdminMemberController::class, 'create'])->name('members.create');
        Route::get('members/{member}/edit', [AdminMemberController::class, 'edit'])->name('members.edit');
        Route::resource('members', AdminMemberController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('trainers/create', [AdminTrainerController::class, 'create'])->name('trainers.create');
        Route::get('trainers/{trainer}/edit', [AdminTrainerController::class, 'edit'])->name('trainers.edit');
        Route::resource('trainers', AdminTrainerController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('packages/create', [AdminGymPackageController::class, 'create'])->name('packages.create');
        Route::get('packages/{package}/edit', [AdminGymPackageController::class, 'edit'])->name('packages.edit');
        Route::resource('packages', AdminGymPackageController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('registrations/create', [AdminRegistrationController::class, 'create'])->name('registrations.create');
        Route::get('registrations/{registration}/edit', [AdminRegistrationController::class, 'edit'])->name('registrations.edit');
        Route::resource('registrations', AdminRegistrationController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('schedules/create', [AdminScheduleController::class, 'create'])->name('schedules.create');
        Route::get('schedules/{schedule}/edit', [AdminScheduleController::class, 'edit'])->name('schedules.edit');
        Route::resource('schedules', AdminScheduleController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('payments/create', [AdminPaymentController::class, 'create'])->name('payments.create');
        Route::get('payments/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('payments.edit');
        Route::resource('payments', AdminPaymentController::class)->only(['index', 'store', 'update', 'destroy']);

        Route::get('reviews/create', [AdminReviewController::class, 'create'])->name('reviews.create');
        Route::get('reviews/{review}/edit', [AdminReviewController::class, 'edit'])->name('reviews.edit');
        Route::resource('reviews', AdminReviewController::class)->only(['index', 'store', 'update', 'destroy']);
    });

require __DIR__.'/auth.php';
