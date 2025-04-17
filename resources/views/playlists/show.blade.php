<x-app-layout>
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $playlist->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    <div>
                        <h2 class="text-xl font-bold">{{ $playlist->name }}</h2>
                        <p class="text-gray-400">Created by {{ $playlist->user->name ?? 'Unknown' }}</p>
                    </div>
                    <div class="flex items-center sm:space-x-2 xs:space-x-0">
                        <button class="border border-gray-100 rounded-sm px-2" id="playBtn">Play</button>
                        @if(auth()->id() == $playlist->user_id)
                        <button class="border border-gray-100 rounded-sm px-2">
                            <a href="{{ route('playlists.add-song', $playlist->id) }}">
                                Add Song
                            </a>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            @if($playlist->songs->count() > 0)
            <div class="py-4 mt-2">
                <ul class="">
                    @foreach($playlist->songs as $song)
                    <li class="py-2 border-b border-gray-700 dark:border-gray-600">
                        <div class="song-item xs:flex-col sm:flex justify-between items-center px-4 rounded-sm">
                            <div>
                                <h3 class="text-white">{{ $song->title }}</h3>
                                <p class="text-gray-400">{{ $song->artist }}</p>
                            </div>
                            <div class="flex items-center space-x-4">
                                @if($song->url)
                                <audio controls src="{{ $song->url }}" class="h-8" />
                                @endif
                            </div>
                            @if(auth()->id() == $playlist->user_id)
                            <form method="POST" action="{{ route('playlists.remove-song', [$playlist->id, $song->id]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dark:text-gray-100">Remove</button>
                            </form>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="p-6 text-gray-500 dark:text-gray-400 text-center bg-white dark:bg-gray-800 mt-4 shadow-sm sm:rounded-lg">
                This playlist doesn't have any songs yet.
            </div>
            @endif

            <div class="mt-4 flex justify-between">
                <a href="{{ url()->previous() }}" class="dark:text-gray-100 hover:underline px-2">Back</a>

                @if(auth()->id() == $playlist->user_id)
                <form method="POST" action="{{ route('playlists.destroy', $playlist->id) }}" onsubmit="return confirm('Are you sure you want to delete this playlist?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dark:text-gray-100 px-2">Delete Playlist</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>