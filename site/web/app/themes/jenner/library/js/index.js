import "./countdown-livestream";
import "./share";
import "./search";
import "./audio";
import "./theater";

const start = () => {
    // Setup event delegation on body.
    document.documentElement.dispatchEvent(new CustomEvent("jenner:initial"));
    // Setup activity which requires setup on each page change.
    document.documentElement.dispatchEvent(new CustomEvent("jenner:load"));
};

if (
    document.readyState === "complete" ||
    document.readyState === "loaded" ||
    document.readyState === "interactive"
) {
    start();
} else {
    document.addEventListener("DOMContentLoaded", start);
}
