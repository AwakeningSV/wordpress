const enhanceAudioPlayerWithTrackData = (audio) => {
    let artist = "";

    try {
        const teachers = document.querySelectorAll(".teachers a");
        artist = Array.from(teachers)
            .map((node) => node.textContent)
            .join(", ");
    } catch (ex) {}

    let album = "";
    try {
        album = document.querySelector(".series a").textContent;
    } catch (ex) {}

    const title = document.querySelector(".page-title").textContent;

    audio.dataset.artist = artist;
    audio.dataset.album = album;
    audio.dataset.title = title;

    try {
        audio.dataset.artwork =
            document.querySelector(".byline").dataset.artwork;
    } catch (ex) {}
};

const enhanceAudioPlayerWithAnalytics = (audio, log) => {
    let updateTimeout;
    let seekTimeout;

    const getUpdateInterval = () => {
        const { duration, currentTime } = audio;

        if (!duration) return 1000;

        return Math.min(
            Math.floor(duration * 0.25) * 1000,
            (duration - currentTime) * 1000
        );
    };

    const progress = () => {
        const { duration, currentTime } = audio;
        const t = Math.floor((currentTime / duration) * 4) / 4;
        const percent = t * 100;

        if (audio.dataset.percent !== String(percent)) {
            log(percent + "% Played");
            audio.dataset.percent = String(percent);
        }

        const delay = getUpdateInterval();

        if (delay > 0) setTimeout(progress, delay);
    };

    audio.addEventListener("play", () => {
        log("Audio Play");

        const delay = getUpdateInterval();

        if (delay > 0) updateTimeout = setTimeout(progress, delay);
    });

    audio.addEventListener("pause", () => {
        log("Audio Pause");
        clearInterval(updateTimeout);
    });

    const seeked = () => {
        clearInterval(updateTimeout);
        clearInterval(seekTimeout);
        seekTimeout = setTimeout(() => {
            log("Audio Seek");
            progress();
        }, 2000);
    };

    audio.addEventListener("seeked", seeked);

    // Listen for various MediaSession seeks.
    document.documentElement.addEventListener("jenner:audioseeked", seeked);
};

const enhanceAudioPlayer = () => {
    const audio = document.querySelector("audio");

    if (!audio) return;

    // If events are already attached, no need to re-add them.
    if (audio.dataset.enhanced) return;

    enhanceAudioPlayerWithTrackData(audio);

    const log = (origin, action) => {
        document.documentElement.dispatchEvent(
            new CustomEvent("jenner:activity", {
                detail: {
                    event: "Audio",
                    origin,
                    action,
                    label: [
                        audio.dataset.title,
                        audio.dataset.artist,
                        audio.dataset.album,
                    ]
                        .filter((v) => !!v)
                        .join(" - "),
                },
            })
        );
    };

    if (navigator.mediaSession) {
        enhanceAudioPlayerWithMediaSession(
            audio,
            log.bind(null, "MediaSession")
        );
    }

    enhanceAudioPlayerWithAnalytics(audio, log.bind(null, "Player"));

    audio.dataset.enhanced = true;
};

/*!
Contains software by Google LLC.
Used under the Apache 2.0 license: https://github.com/GoogleChrome/samples/blob/83ecaff10e0f27af41797e6177ae7feeacc412e8/LICENSE
*/
/**
 * Add Media Session support for audio element on teaching page.
 *
 * https://web.dev/media-session/
 *
 * Adapted from an Apache 2.0 licensed sample: https://googlechrome.github.io/samples/media-session/audio.html
 */

const enhanceAudioPlayerWithMediaSession = (audio, log) => {
    const updatePositionState = () => {
        if ("setPositionState" in navigator.mediaSession) {
            try {
                navigator.mediaSession.setPositionState({
                    duration: audio.duration,
                    playbackRate: audio.playbackRate,
                    position: audio.currentTime,
                });
            } catch (ex) {
                // Non-finite position provided on initial start.
                if (ex.message.indexOf("non-finite")) return;
                // We want to know about other errors.
                throw ex;
            }
        }
    };

    audio.addEventListener("play", () => {
        const { title, artist, album } = audio.dataset;

        let thumbnail = {};

        try {
            const artwork = JSON.parse(audio.dataset.artwork);
            thumbnail.src = artwork[0];
            thumbnail.sizes = `${artwork[1]}x${artwork[2]}`;
        } catch (ex) {}

        navigator.mediaSession.metadata = new MediaMetadata({
            title,
            artist,
            album,
            artwork: [thumbnail],
        });

        navigator.mediaSession.playbackState = "playing";

        updatePositionState();
    });

    audio.addEventListener("pause", () => {
        navigator.mediaSession.playbackState = "paused";
    });

    let defaultSkipTime = 10; /* Time to skip in seconds */

    navigator.mediaSession.setActionHandler("seekbackward", (event) => {
        log("Audio Seek Backward");
        document.documentElement.dispatchEvent(
            new CustomEvent("jenner:audioseeked")
        );

        const skipTime = event.seekOffset || defaultSkipTime;
        audio.currentTime = Math.max(audio.currentTime - skipTime, 0);
        updatePositionState();
    });

    navigator.mediaSession.setActionHandler("seekforward", (event) => {
        log("Audio Seek Forward");
        document.documentElement.dispatchEvent(
            new CustomEvent("jenner:audioseeked")
        );

        const skipTime = event.seekOffset || defaultSkipTime;
        audio.currentTime = Math.min(
            audio.currentTime + skipTime,
            audio.duration
        );
        updatePositionState();
    });

    navigator.mediaSession.setActionHandler("play", async () => {
        log("Audio Play");
        await audio.play();
    });

    navigator.mediaSession.setActionHandler("pause", () => {
        log("Audio Pause");
        audio.pause();
    });

    try {
        navigator.mediaSession.setActionHandler("seekto", (event) => {
            document.documentElement.dispatchEvent(
                new CustomEvent("jenner:audioseeked")
            );
            if (event.fastSeek && "fastSeek" in audio) {
                log("Audio Fast Seek");
                audio.fastSeek(event.seekTime);
                return;
            }
            log("Audio Seek");
            audio.currentTime = event.seekTime;
            updatePositionState();
        });
    } catch (ex) {}
};

document.documentElement.addEventListener("jenner:load", enhanceAudioPlayer);

document.documentElement.addEventListener(
    "jenner:audiochanged",
    enhanceAudioPlayer
);
