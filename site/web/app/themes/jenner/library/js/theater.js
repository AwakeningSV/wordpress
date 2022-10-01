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

    switcher.addEventListener("click", (ev) => {
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
    });
};

document.documentElement.addEventListener("jenner:load", setupMediaSwitcher);
