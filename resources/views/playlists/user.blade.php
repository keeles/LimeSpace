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
            {{ __('User Playlists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    <div>{{ __("Playlists for ") }} {{ $user->name ?? 'User' }}</div>
                    <button>
                        <a href="/playlists/new">
                            +
                        </a>
                    </button>
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
                                <span class="text-gray-400">{{ $playlist->songs->count() }} songs</span>
                                <a href="{{ route('playlists.show', $playlist->id) }}" class="text-blue-500 hover:underline">View</a>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="p-6 text-gray-500 dark:text-gray-400 text-center">
                No playlists found for this user.
            </div>
            @endif
        </div>
    </div>
</x-app-layout>