<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use App\Services\SongService as SongService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PlaylistController extends Controller
{
    protected $songService;

    public function __construct(SongService $songService)
    {
        $this->songService = $songService;
    }
    function all()
    {
        $playlists = Playlist::all();
        return View("playlists.all", ['playlists' => $playlists]);
    }
    function findByUser($userId)
    {
        $playlists = DB::table('playlist')->where('user_id', '=', $userId)->get();
        return View('playlist.user', ['playlists' => $playlists]);
    }
    function findBySong($songId)
    {
        $song = Song::with('playlists')->findOrFail($songId);
        $this->songService->addPresignedUrls($song);
        $playlists = $song->playlists;
        return View('playlists.song', ['playlists' => $playlists, 'song' => $song]);
    }
    function findById($playlistId)
    {
        $playlist = Playlist::with('songs')->find($playlistId);
        $this->songService->addPresignedUrls($playlist->songs);
        if ($playlist && $playlist->songs) {
            $this->songService->addPresignedUrls($playlist->songs);
        }
        return View('playlists.show', ['playlist' => $playlist]);
    }
    function addSongForm($playlistId, Request $request,)
    {
        $user = $request->user();
        $playlist = Playlist::with('songs')->find($playlistId);
        $songs = $user->songs()->latest()->get();
        return View('playlists.newsong', ['playlist' => $playlist, 'songs' => $songs]);
    }
    function addSong($playlistId, Request $request)
    {
        $songId = $request['song'];
        $exists = DB::table('playlist_song')
            ->where('playlist_id', $playlistId)
            ->where('song_id', $songId)
            ->exists();

        if (!$exists) {
            DB::table('playlist_song')->insert([
                'playlist_id' => $playlistId,
                'song_id' => $songId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return redirect()->route('playlists.show', $playlistId)
            ->with('success', 'Song added to playlist successfully');
    }

    function new(Request $request)
    {
        //TODO: handle unauth user
        // Gate::authorize('create', Song::class);
        $user = $request->user();
        $name = $request['name'];
        $playlist = Playlist::create([
            'name' => $name,
            'user_id' => $user->id
        ]);
        $playlist->save();
        return View('playlists.show', ['playlist' => $playlist]);
    }
    function edit() {}
}
