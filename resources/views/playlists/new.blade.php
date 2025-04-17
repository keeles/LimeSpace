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
            {{ __('Add Playlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex justify-between">
                    {{ __("Playlist Name") }}
                </div>
                <div>
                    <form action="/playlists/new" method="POST" enctype="multipart/form-data" class="flex flex-col p-2 text-gray-900 dark:text-gray-100">
                        @csrf
                        <div class="flex justify-between">
                            <label for="name">Playlist Title:</label>
                            <button type="submit" class="border border-gray-100 rounded-sm px-2 mb-1">
                                Create Playlist
                            </button>
                        </div>
                        <input class="rounded dark:bg-gray-900 mx-2" type="text" name="name">
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>