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
            {{ __('Add Song') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    {{ __("All Songs") }}
                </div>
                <div class="text-gray-900 dark:text-gray-100">
                    @if($songs->count() > 0)
                    @foreach($songs as $song)
                    <div class="song-item flex justify-between p-4 border-b border-gray-100 dark:bg-gray-900">
                        <h3>{{ $song->title }} - {{ $song->artist }}</h3>
                        <audio controls src="{{ $song->url }}" />
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="dark:bg-gray-900">
                <p class="p-6 text-gray-500 dark:text-gray-400 text-center">No songs available.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>