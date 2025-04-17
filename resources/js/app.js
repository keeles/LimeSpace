document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelectorAll("audio").length > 0) {
        initMusicPlayer();
    }
});

function initMusicPlayer() {
    const audioElements = document.querySelectorAll("audio");
    const songItems = document.querySelectorAll(".song-item");

    if (audioElements.length === 0) {
        return;
    }

    let currentAudioIndex = 0;
    let playerControlsVisible = false;
    let playerControls = null;

    const originalPlayBtn = document.querySelector("button#playBtn");
    if (!originalPlayBtn) {
        return;
    }

    function createPlayerControls() {
        playerControls = document.createElement("div");
        playerControls.className =
            "flex items-center justify-between space-x-3 my-4";
        playerControls.id = "playerControlsPanel";
        playerControls.style.display = "none";
        playerControls.innerHTML = `
        <div class="xs:flex-col sm:flex pl-2 sm:space-x-2 xs:space-y-1">
            <button id="prevBtn" class="border border-gray-100 dark:text-gray-100 rounded-sm px-2">Prev</button>
            <button id="playerPauseBtn" class="border border-gray-100 dark:text-gray-100 rounded-sm px-2">Pause</button>
            <button id="nextBtn" class="border border-gray-100 dark:text-gray-100 rounded-sm px-2">Next</button>
        </div>
            <div class="flex items-center space-x-2">
                <span class="text-gray-300">Volume:</span>
                <input type="range" id="volumeControl" min="0" max="1" step="0.1" value="1" class="w-24">
            </div>
        `;

        const songListContainer = document.querySelector("ul").parentElement;
        if (songListContainer) {
            songListContainer.parentNode.insertBefore(
                playerControls,
                songListContainer
            );
        } else {
            return false;
        }

        document
            .getElementById("prevBtn")
            .addEventListener("click", playPrevious);
        document.getElementById("nextBtn").addEventListener("click", playNext);
        document
            .getElementById("playerPauseBtn")
            .addEventListener("click", togglePlayPause);

        const volumeControl = document.getElementById("volumeControl");
        volumeControl.addEventListener("input", function () {
            const volume = parseFloat(this.value);
            audioElements.forEach((audio) => (audio.volume = volume));
        });

        return true;
    }

    function showPlayerControls() {
        if (!playerControls) {
            if (!createPlayerControls()) {
                return;
            }
        }

        playerControls.style.display = "flex";
        originalPlayBtn.textContent = "Stop";
        playerControlsVisible = true;
    }

    function hidePlayerControls() {
        if (playerControls) {
            playerControls.style.display = "none";
            playerControlsVisible = false;
            originalPlayBtn.textContent = "Play";
        }
    }

    function stopAndHidePlayer() {
        pausePlayback();
        hidePlayerControls();
        songItems.forEach((item) => item.classList.remove("bg-gray-800"));
    }

    function playCurrentAudio() {
        showPlayerControls();

        audioElements.forEach((audio) => audio.pause());

        songItems.forEach((item) => item.classList.remove("bg-gray-800"));

        const currentAudio = audioElements[currentAudioIndex];
        currentAudio.play();

        if (songItems[currentAudioIndex]) {
            songItems[currentAudioIndex].classList.add("bg-gray-800");
        }
    }

    function pausePlayback() {
        audioElements[currentAudioIndex].pause();
    }

    function togglePlayPause() {
        const currentAudio = audioElements[currentAudioIndex];
        const pauseBtn = document.getElementById("playerPauseBtn");

        if (currentAudio.paused) {
            currentAudio.play();
            pauseBtn.textContent = "Pause";
        } else {
            currentAudio.pause();
            pauseBtn.textContent = "Play";
        }
    }

    function playNext() {
        const currentAudio = audioElements[currentAudioIndex];
        currentAudioIndex++;
        currentAudio.currentTime = 0;
        if (currentAudioIndex >= audioElements.length) {
            currentAudioIndex = 0;
        }
        playCurrentAudio();
    }

    function playPrevious() {
        const currentAudio = audioElements[currentAudioIndex];
        currentAudioIndex--;
        currentAudio.currentTime = 0;
        if (currentAudioIndex < 0) {
            currentAudioIndex = audioElements.length - 1;
        }
        playCurrentAudio();
    }

    function formatTime(seconds) {
        const minutes = Math.floor(seconds / 60);
        const remainingSeconds = Math.floor(seconds % 60);
        return `${minutes}:${remainingSeconds.toString().padStart(2, "0")}`;
    }

    function updateProgressBar() {
        if (!playerControlsVisible) return;

        const currentAudio = audioElements[currentAudioIndex];
        const progressBar = document.getElementById("progressBar");
        const currentTimeDisplay = document.getElementById("currentTime");

        if (progressBar && currentTimeDisplay) {
            const progress =
                (currentAudio.currentTime / currentAudio.duration) * 100;
            progressBar.style.width = `${progress}%`;

            currentTimeDisplay.textContent = `${formatTime(
                currentAudio.currentTime
            )} / ${formatTime(currentAudio.duration || 0)}`;
        }
    }

    originalPlayBtn.addEventListener("click", (e) => {
        e.preventDefault();
        originalPlayBtn.textContent === "Play"
            ? playCurrentAudio()
            : stopAndHidePlayer();
    });

    audioElements.forEach((audio, index) => {
        audio.addEventListener("timeupdate", updateProgressBar);

        audio.addEventListener("ended", function () {
            playNext();
        });
    });

    songItems.forEach((item, index) => {
        item.style.cursor = "pointer";
        item.addEventListener("click", function (e) {
            if (e.target.tagName !== "BUTTON" && !e.target.closest("button")) {
                currentAudioIndex = index;
                playCurrentAudio();
            }
        });
    });
}
