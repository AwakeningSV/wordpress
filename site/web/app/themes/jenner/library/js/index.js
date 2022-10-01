import "./countdown-livestream";
import "./share";
import "./search";
import "./audio";
import "./theater";

document.addEventListener("DOMContentLoaded", () => {
    // Setup event delegation on body.
    document.documentElement.dispatchEvent(new CustomEvent("jenner:initial"));
    // Setup activity which requires setup on each page change.
    document.documentElement.dispatchEvent(new CustomEvent("jenner:load"));
});
