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
const enhanceAudioPlayerWithMediaSession = () => {
    const audio = document.querySelector('audio');

    if (!audio) return;
    if (!navigator.mediaSession) return;

    let thumbnail = {};

    try {
        const artwork = JSON.parse(document.querySelector('.byline').dataset.artwork);
        thumbnail.src = artwork[0];
        thumbnail.sizes = `${artwork[1]}x${artwork[2]}`;
    } catch (ex) {}

    const updatePositionState = () => {
        if ('setPositionState' in navigator.mediaSession) {
            try {
                navigator.mediaSession.setPositionState({
                    duration: audio.duration,
                    playbackRate: audio.playbackRate,
                    position: audio.currentTime
                });
            } catch (ex) {
                // Non-finite position provided on initial start.
                if (ex.message.indexOf('non-finite')) return;
                // We want to know about other errors.
                throw ex;
            }
        }
    }

    audio.addEventListener('play', () => {
        let artist = '';

        try {
            const teachers = document.querySelectorAll('.teachers a');
            artist = Array.from(teachers).map(node => node.textContent).join(', ');
        } catch (ex) {}

        let album = '';
        try {
            album = document.querySelector('.series a').textContent;
        } catch (ex) {}


        navigator.mediaSession.metadata = new MediaMetadata({
            title: document.querySelector('.page-title').textContent,
            artist,
            album,
            artwork: [thumbnail]
        });

        navigator.mediaSession.playbackState = 'playing';

        updatePositionState();
    });

    audio.addEventListener('pause', () => {
        navigator.mediaSession.playbackState = 'paused';
    });

    let defaultSkipTime = 10; /* Time to skip in seconds */

    navigator.mediaSession.setActionHandler('seekbackward', event => {
        const skipTime = event.seekOffset || defaultSkipTime;
        audio.currentTime = Math.max(audio.currentTime - skipTime, 0);
        updatePositionState();
    });

    navigator.mediaSession.setActionHandler('seekforward', event => {
        const skipTime = event.seekOffset || defaultSkipTime;
        audio.currentTime = Math.min(audio.currentTime + skipTime, audio.duration);
        updatePositionState();
    });

    navigator.mediaSession.setActionHandler('play', async () => {
        await audio.play();
    });

    navigator.mediaSession.setActionHandler('pause', () => {
        audio.pause();
    });

    try {
        navigator.mediaSession.setActionHandler('seekto', event => {
            if (event.fastSeek && ('fastSeek' in audio)) {
                audio.fastSeek(event.seekTime);
                return;
            }
            audio.currentTime = event.seekTime;
            updatePositionState();
        });
    } catch (ex) {}
};

document.addEventListener('DOMContentLoaded', enhanceAudioPlayerWithMediaSession);