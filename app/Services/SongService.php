<?php

namespace App\Services;

use App\Models\Song;
use Illuminate\Support\Facades\Storage;

class SongService
{
    public function addPresignedUrls($songs, $minutes = 60)
    {
        if (!$songs) {
            return $songs;
        }

        // Handle both collections and individual models
        if ($songs instanceof Song) {
            $songs->url = Storage::disk("s3")->temporaryUrl(
                $songs->filePath,
                now()->addMinutes($minutes)
            );
            return $songs;
        }

        // Handle collections of songs
        foreach ($songs as $song) {
            $song->url = Storage::disk("s3")->temporaryUrl(
                $song->filePath,
                now()->addMinutes($minutes)
            );
        }

        return $songs;
    }
}
