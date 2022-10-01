import "./countdown-livestream";
import "./share";
import "./search";
import "./audio";
import "./theater";

document.addEventListener("DOMContentLoaded", () => {
    document.documentElement.dispatchEvent(new CustomEvent("jenner:load"));
});
