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
                    {{ __("Song Info") }}
                </div>
                @if($songs->count() > 0)
                <div class="p-4 border-b border-black dark:border-gray-100">
                    <ul>
                        @foreach($songs as $song)
                        <li class="px-4">
                            <form method="POST" action="{{ route('playlists.store-song', $playlist->id) }}" class="flex justify-between">
                                <h3 class="text-gray-900 dark:text-gray-100">{{ $song->title }} - {{ $song->artist }}</h3>
                                <input type="hidden" name="song" id="song" value="{{ $song->id }}">
                                @csrf
                                <button class="text-gray-900 dark:text-gray-100 border border-gray-100 rounded-sm px-2">
                                    Add to Playlist
                                </button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div>
                    @include('partials.songform');
                </div>
            </div>
        </div>
    </div>
</x-app-layout>