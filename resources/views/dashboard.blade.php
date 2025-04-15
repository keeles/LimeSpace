<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    {{ __("Songs") }}
                    <button>
                        <a href="/songs/new">
                            +
                        </a>
                    </button>
                </div>
            </div>
            @if($songs->count() > 0)
            <div class="p-4 border-b border-black dark:border-gray-100">
                <ul>
                    @foreach($songs as $song)
                    <li class="px-4">
                        <div class="song-item flex justify-between">
                            <h3 class="text-white">{{ $song->title }} - {{ $song->artist }}</h3>
                            <audio controls src="{{ $song->url }}" />
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    {{ __("Playlists") }}
                    <button>
                        <a href="/playlist/new">
                            +
                        </a>
                    </button>
                </div>
            </div>
            @if($playlists->count() > 0)
            <div class="p-4 border-b border-black dark:border-gray-100">
                <ul>
                    @foreach($playlists as $playlist)
                    <li class="px-4">
                        <div class="song-item flex justify-between">
                            <h3 class="text-gray-900 dark:text-gray-100"><a href="{{ route('playlists.show', $playlist->id) }}">{{ $playlist->name }}</a></h3>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>