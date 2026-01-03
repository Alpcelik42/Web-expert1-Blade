<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\EventCollaboratorController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

// Debug route
Route::get('/debug-test', function() {
    return "Debug test werkt!";
});

// Public routes
Route::get('/', function () {
    return redirect()->route('events.index');
})->name('home');

Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
Route::get('/events/search', [EventController::class, 'search'])->name('events.search');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Wachtwoord reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::post('/verify-security-answer', [AuthController::class, 'verifySecurityAnswer'])->name('password.verify.answer');
    Route::post('/reset-password-direct', [AuthController::class, 'resetPasswordDirect'])->name('password.reset.direct');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Event routes
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

    // Event image routes
    Route::delete('/event-images/{image}', [EventController::class, 'deleteImage'])->name('events.image.delete');
    Route::post('/event-images/{image}/set-primary', [EventController::class, 'setPrimaryImage'])->name('events.image.set-primary');

    // Favorite routes
    Route::post('/events/{event}/favorite', [EventController::class, 'toggleFavorite'])->name('events.favorite');

    // Ticket routes
    Route::get('/events/{event}/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/events/{event}/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/events/{event}/tickets/{ticket}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    Route::put('/events/{event}/tickets/{ticket}', [TicketController::class, 'update'])->name('tickets.update');
    Route::delete('/events/{event}/tickets/{ticket}', [TicketController::class, 'destroy'])->name('tickets.destroy');

    // Ticket booking - CORRECTIE: dit moet naar BookingController gaan
    Route::post('/events/{event}/tickets/{ticket}/book', [BookingController::class, 'bookTicket'])->name('tickets.book');

    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    // In de auth middleware groep:
    Route::delete('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');


    // Collaborator routes (Extra feature)
    Route::get('/events/{event}/collaborators', [EventCollaboratorController::class, 'index'])->name('events.collaborators.index');
    Route::post('/events/{event}/collaborators', [EventCollaboratorController::class, 'store'])->name('events.collaborators.store');
    Route::delete('/events/{event}/collaborators/{collaborator}', [EventCollaboratorController::class, 'destroy'])->name('events.collaborators.destroy');
});

// Public event show route (moet buiten auth middleware)
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
