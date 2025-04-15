<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\SongService as SongService;

class DashboardController extends Controller
{
    protected $songService;

    public function __construct(SongService $songService)
    {
        $this->songService = $songService;
    }
    public function index(Request $request): View
    {
        $user = $request->user();

        $songs = $user->songs()->latest()->get();
        $this->songService->addPresignedUrls($songs);
        $playlists = $user->playlists()->with('songs')->latest()->get();

        return View('dashboard', [
            'songs' => $songs,
            'playlists' => $playlists,
        ]);
    }
}
