<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    /** @use HasFactory<\Database\Factories\SongFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'artist',
        'filePath',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'user_song');
    }

    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_song')->withTimestamps();
    }
}
