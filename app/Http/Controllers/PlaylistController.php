<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use App\Models\User;
use App\Services\SongService as SongService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

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
        Gate::authorize('update', [Playlist::class, $playlist]);
        $songs = $user->songs()->latest()->get();
        return View('playlists.newsong', ['playlist' => $playlist, 'songs' => $songs]);
    }
    function addSong($playlistId, Request $request)
    {
        $playlist = Playlist::with('songs')->find($playlistId);
        Gate::authorize('update', [Playlist::class, $playlist]);
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

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Song added to playlist successfully');
    }
    function new(Request $request)
    {
        Gate::authorize('create', Playlist::class);
        $user = $request->user();
        $name = $request['name'];
        $playlist = Playlist::create([
            'name' => $name,
            'user_id' => $user->id
        ]);
        $playlist->save();
        return View('playlists.show', ['playlist' => $playlist]);
    }
    function removeSong($playlistId, $songId)
    {
        $playlist = Playlist::with('songs')->find($playlistId);
        Gate::authorize('update', [Playlist::class, $playlist]);
        try {
            DB::table('playlist_song')
                ->where('playlist_id', $playlistId, 'and')
                ->where('song_id', $songId)->delete();
            return View('playlists.show', ['playlist' => $playlist]);
        } catch (\Exception $e) {
            return View('playlists.show', ['playlist' => $playlist]);
        }
    }
    function destroy($playlistId)
    {
        $playlist = Playlist::with('songs')->find($playlistId);
        Gate::authorize('delete', [Playlist::class, $playlist]);
        $destroyed = Playlist::destroy($playlistId);
        if (!$destroyed) {
            return redirect()->route('playlists.show', $playlistId)
                ->with('Error', 'There was an error deleting your playlist, please try again.');
        }
        return redirect('dashboard');
    }
}
