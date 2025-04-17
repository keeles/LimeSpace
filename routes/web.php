<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::get('dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::prefix('songs')->name('songs.')->group(function () {
    Route::get('/', [SongController::class, 'all'])->name('all');
    Route::get('/new', function () {
        return View("songs.new");
    });
    Route::post('/new', [SongController::class, 'new'])->name('new');
    Route::delete('/{id}', [SongController::class, 'delete'])->name('delete');
    Route::get('/{id}', [SongController::class, 'show'])->name('show');
});

Route::prefix('playlists')->name('playlists.')->group(function () {
    Route::get('/', [PlaylistController::class, 'all'])->name('all');
    Route::get('/new', function () {
        return View("playlists.new");
    });
    Route::post('/new', [PlaylistController::class, 'new']);
    Route::post('/', [PlaylistController::class, 'store'])->name('store');
    Route::get('/{id}', [PlaylistController::class, 'findById'])->name('show');
    Route::put('/{id}', [PlaylistController::class, 'update'])->name('update');
    Route::delete('/{id}', [PlaylistController::class, 'destroy'])->name('destroy');

    Route::get('/user/{id}', [PlaylistController::class, 'findByUser'])->name('user');
    Route::get('/song/{id}', [PlaylistController::class, 'findBySong'])->name('by-song');

    Route::get('/{id}/add-song', [PlaylistController::class, 'addSongForm'])->name('add-song');
    Route::post('/{id}/add-song', [PlaylistController::class, 'addSong'])->name('store-song');
    Route::delete('/{playlist_id}/songs/{song_id}', [PlaylistController::class, 'removeSong'])->name('remove-song');
});

require __DIR__ . '/auth.php';
