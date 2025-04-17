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
            {{ __('Playlists with: ') }} {{ $song->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="song-details mb-4">
                        <h3 class="text-lg font-semibold">{{ $song->title }}</h3>
                        <p class="text-gray-400">By {{ $song->artist }}</p>
                        @if($song->url)
                        <div class="mt-2">
                            <audio controls src="{{ $song->url }}" class="w-full" />
                        </div>
                        @endif
                    </div>

                    <h3 class="text-xl mt-6 mb-2">{{ __("Found in these playlists:") }}</h3>
                </div>
            </div>

            @if($playlists->count() > 0)
            <div class="p-4 border-b border-black dark:border-gray-100">
                <ul>
                    @foreach($playlists as $playlist)
                    <li class="px-4 py-2">
                        <div class="playlist-item flex justify-between">
                            <h3 class="text-white">{{ $playlist->name }}</h3>
                            <div class="flex space-x-3">
                                <span class="text-gray-400">By {{ $playlist->user->name ?? 'Unknown' }}</span>
                                <a href="{{ route('playlists.show', $playlist->id) }}" class="text-blue-500 hover:underline">View</a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="p-6 text-gray-500 dark:text-gray-400 text-center bg-white dark:bg-gray-800 mt-4 shadow-sm sm:rounded-lg">
                This song isn't in any playlists yet.
            </div>
            @endif

            <div class="mt-4 text-right">
                <a href="{{ route('songs.show', $song->id) }}" class="text-blue-500 hover:underline">Back to song</a>
            </div>
        </div>
    </div>
</x-app-layout>