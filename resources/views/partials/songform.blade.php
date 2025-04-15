<form action="/songs/new" method="POST" enctype="multipart/form-data" class="flex flex-col p-2 text-gray-900 dark:text-gray-100">
    @csrf
    <label for="title">Song Title:</label>
    <input class="rounded dark:bg-gray-900 mx-2" type="text" name="title">
    <label for="artist">Artist:</label>
    <input class="rounded dark:bg-gray-900 mx-2" type="text" name="artist">
    <div class="flex justify-between pt-2">
        <div>
            <label for="song">Song File:</label>
            <input class="rounded mx-2 px-2" type="file" name="song" accept=".mp3">
        </div>
        <button type="submit" class="">
            Add Song
        </button>
    </div>
</form>