const setupMediaSwitcher = () => {
    const switcher = document.querySelector(".teaching-content-option");
    if (!switcher) return;

    const theater = document.querySelector(".teaching-theater");

    const toggle = () =>
        Array.from(switcher.childNodes).forEach((node) => {
            if (!node.classList) return;
            node.classList.toggle("teaching-content-option-selected");
            const checked = node.getAttribute("aria-checked");
            node.setAttribute("aria-checked", !checked);
        });

    const handleClick = (ev) => {
        if (
            ev.target.classList &&
            ev.target.classList.contains("teaching-content-option-selected")
        )
            return;
        toggle();
        if (ev.target.textContent === "Video") {
            theater.classList.remove("teaching-theater-audio");
            theater.classList.add("teaching-theater-video");
        } else {
            theater.classList.add("teaching-theater-audio");
            theater.classList.remove("teaching-theater-video");
        }
    };

    document.body.addEventListener("click", (ev) => {
        if (
            ev.target.parentNode &&
            ev.target.parentNode.classList.contains("teaching-content-option")
        ) {
            handleClick(ev);
        }
    });
};

const setupStickyScroll = () => {
    const $ = window.jQuery;

    let wrap = document.querySelector(".embed-wrap");
    if (!wrap) wrap = document.querySelector(".embed-video");

    if (!wrap) return () => {};

    const player = wrap.querySelector("iframe");

    if (!player) return () => {};

    const top = $(wrap).offset().top;
    const offset = Math.floor(top + $(wrap).outerHeight() / 2);

    const scroll = window.addEventListener("scroll", () => {
        if (
            $(window).scrollTop() > offset &&
            player.classList.contains("is-playing")
        ) {
            player.classList.add("stuck");
        } else {
            player.classList.remove("stuck");
        }
    });

    return () => {
        window.removeEventListener("scroll", scroll);
    };
};

const setupYouTubeIframes = () => {
    for (var e = document.getElementsByTagName("iframe"), x = e.length; x--; )
        if (/youtube.*\/embed/.test(e[x].src)) {
            if (e[x].src.indexOf("enablejsapi=") === -1)
                e[x].src +=
                    (e[x].src.indexOf("?") === -1 ? "?" : "&") +
                    "enablejsapi=1";
        }
};

const isYouTubeIframePresent = () => {
    for (var e = document.getElementsByTagName("iframe"), x = e.length; x--; )
        if (/youtube.*\/embed/.test(e[x].src)) return true;

    return false;
};

const ifNeededLoadYouTubeAPI = () => {
    if (window.YT) return;
    if (!isYouTubeIframePresent()) return;

    var j = document.createElement("script"),
        f = document.getElementsByTagName("script")[0];
    j.src = "https://www.youtube.com/iframe_api";
    j.async = true;
    f.parentNode.insertBefore(j, f);
};

const updateVideoClassNames = (e) => {
    let player = document.querySelector(".embed-wrap iframe");
    if (!player) return;

    if (e.data == YT.PlayerState.PLAYING) {
        player.classList.remove("is-paused");
        player.classList.add("is-playing");
    } else if (e.data == YT.PlayerState.PAUSED) {
        player.classList.remove("is-playing");
        player.classList.add("is-paused");
    }
};

const attachYouTubeEvents = () => {
    if (!window._mtm) window._mtm = [];

    function onPlayerStateChange(e) {
        e["data"] == YT.PlayerState.PLAYING &&
            setTimeout(onPlayerPercent, 1000, e["target"]);
        var video_data = e.target["getVideoData"](),
            label = video_data.video_id + ":" + video_data.title;
        if (e["data"] == YT.PlayerState.PLAYING && YT.gtmLastAction == "p") {
            window._mtm.push({
                event: "YouTube",
                action: "Video Play",
                label: label,
            });
            YT.gtmLastAction = "";
        }
        if (e["data"] == YT.PlayerState.PAUSED) {
            window._mtm.push({
                event: "YouTube",
                action: "Video Pause",
                label: label,
            });
            YT.gtmLastAction = "p";
        }

        updateVideoClassNames(e);
    }

    function onPlayerError(e) {
        window._mtm.push({
            event: "YouTube",
            action: "Video Failure",
            label: "youtube:" + e,
        });
    }

    function onPlayerPercent(e) {
        if (e["getPlayerState"]() == YT.PlayerState.PLAYING) {
            var t =
                e["getDuration"]() - e["getCurrentTime"]() <= 1.5
                    ? 1
                    : (
                          Math.floor(
                              (e["getCurrentTime"]() / e["getDuration"]()) * 4
                          ) / 4
                      ).toFixed(2);
            if (!e["lastP"] || t > e["lastP"]) {
                var video_data = e["getVideoData"](),
                    label = video_data.video_id + ":" + video_data.title;
                e["lastP"] = t;
                window._mtm.push({
                    event: "YouTube",
                    action: t * 100 + "% Watched",
                    label: label,
                });
            }
            e["lastP"] != 1 && setTimeout(onPlayerPercent, 1000, e);
        }
    }

    for (var e = document.getElementsByTagName("iframe"), x = e.length; x--; ) {
        if (
            /youtube.*\/embed/.test(e[x].src) &&
            !e[x].dataset.eventHandlersAttached
        ) {
            new YT.Player(e[x], {
                events: {
                    onStateChange: onPlayerStateChange,
                    onError: onPlayerError,
                },
            });
            YT.gtmLastAction = "p";
            e[x].dataset.eventHandlersAttached = true;
        }
    }
};

document.documentElement.addEventListener("jenner:initial", setupMediaSwitcher);

document.documentElement.addEventListener("jenner:load", () =>
    document.documentElement.addEventListener(
        "jenner:unload",
        setupStickyScroll()
    )
);

document.documentElement.addEventListener("jenner:load", setupYouTubeIframes);
document.documentElement.addEventListener(
    "jenner:load",
    ifNeededLoadYouTubeAPI
);

document.documentElement.addEventListener("jenner:load", () => {
    if (window.YT) {
        attachYouTubeEvents();
    } else {
        window.onYouTubeIframeAPIReady = attachYouTubeEvents;
    }
});
