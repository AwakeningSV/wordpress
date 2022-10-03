document.documentElement.addEventListener("jenner:activity", ({ detail }) => {
    if (!window._mtm) {
        window._mtm = [];
    }

    window._mtm.push(detail);
});
