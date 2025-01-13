<?php
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Generate Chirp Route
    Route::post('/chirps/generate', [ChirpController::class, 'generateAIChirp'])->name('chirps.generate');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Chirp Resource Routes
    Route::resource('chirps', ChirpController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->middleware('verified'); // Optional: Only verified users can manage chirps
});

// Verified User Access (Dashboard, etc.)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__.'/auth.php';
