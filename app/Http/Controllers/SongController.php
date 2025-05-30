<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Models\Song;
use App\Models\User;
use App\Services\SongService;
use Illuminate\Support\Facades\Storage as Storage;

class SongController extends Controller
{
    protected $songService;

    public function __construct(SongService $songService)
    {
        $this->songService = $songService;
    }
    function all()
    {
        $songs = Song::all();
        $this->songService->addPresignedUrls($songs);
        return View("songs.all", ['songs' => $songs]);
    }
    function findByUser($userId)
    {
        $songs = DB::table('song')->where('id', '=', $userId)->get();
        $this->songService->addPresignedUrls($songs);
        return View('songs.user', ['songs' => $songs]);
    }
    function findBySong($songId)
    {
        $song = DB::table('song')->where('id', '=', $songId)->get();
        $this->songService->addPresignedUrls($song);
        return View('songs', ['song' => $song]);
    }
    function new(Request $request)
    {
        Gate::authorize('create', Song::class);
        try {
            if ($request->hasFile('song') && $request->file('song')->isValid()) {
                $user = $request->user();
                $title = $request['title'];
                $artist = $request['artist'];
                $fileName = strtolower(str_replace(" ", "-", $artist)) . '-' . strtolower(str_replace(" ", "-", $title));
                $file = $request->file('song');
                $uploaded = Storage::disk("s3")->put($fileName, file_get_contents($file->getRealPath()));
                if (!$uploaded) {
                    throw new \Exception("Failed to upload file to S3");
                }
                $song = Song::create([
                    'title' => $title,
                    'artist' => $artist,
                    'filePath' => $fileName
                ]);
                $song->save();
                DB::table('user_song')->insert([
                    'song_id' => $song->id,
                    'user_id' => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                return redirect('/dashboard');
            } else {
                return back()->withErrors(['song' => 'Please upload a valid song file.']);
            }
        } catch (\Exception $e) {
            Log::error('S3 upload failed: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            return back()->withErrors(['song' => 'Error uploading file, please try again']);
        }
    }
    function delete($songId, Request $request)
    {
        $song = Song::find($songId);
        Gate::authorize('delete', [Song::class, $song]);
        $songOnPlaylist = DB::table('playlist_song')->join('playlists', 'playlist_song.playlist_id', '=', 'playlists.id')->where('playlist_song.song_id', '=', $songId, 'and')->where('playlists.user_id', $request->user()->id)->exists();
        if ($songOnPlaylist) {
            return back()->withErrors(['song' => 'Please remove song from all playlists first.']);
        }
        $destroyed = Song::destroy($songId);
        if (!$destroyed) {
            return back()->withErrors(['song' => 'Error deleting song.']);
        }
        Storage::disk('s3')->delete($song->filePath);
        return redirect('/dashboard');
    }
}
