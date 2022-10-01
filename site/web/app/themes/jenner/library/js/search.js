const replaceOpenIcon = () => {
    // Use the search icon for the teaching header navigation modal open button.
    const searchIcon = document.querySelector("svg.search-icon");
    if (!searchIcon) return;

    const openButton = document.querySelector(
        ".teaching-header .wp-block-navigation__responsive-container-open"
    );
    if (!openButton) return;

    // Remove the default SVG.
    openButton.removeChild(openButton.firstChild);
    openButton.style.alignItems = "center"; // Center text with icon. (Button is flex.)
    openButton.appendChild(document.createTextNode("Search "));
    // Append a clone of the search icon SVG.
    openButton.appendChild(searchIcon.cloneNode(true));
};

const replacePlaceholderText = (teachingSearch) => {
    let count;
    try {
        count = document.querySelector(".teaching-header").dataset.count;
    } catch (ex) {
        return;
    }

    const input = teachingSearch.querySelector("input");
    if (!input) return;

    input.setAttribute("placeholder", `Search ${count} sermons...`);
};

document.addEventListener("DOMContentLoaded", () => {
    const teachingSearch = document.querySelector(
        ".teaching-header .wp-block-search"
    );

    if (teachingSearch) {
        // Limit search to teaching posts.
        const input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "post_type");
        input.setAttribute("value", "teaching");
        teachingSearch.appendChild(input);

        replaceOpenIcon();
        replacePlaceholderText(teachingSearch);
    }
});
