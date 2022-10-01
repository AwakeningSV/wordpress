async function share(url) {
    if (!window._mtm) {
        window._mtm = [];
    }

    window._mtm.push({
        event: "Web Share",
        action: "Share Initiated",
        label: url,
    });

    try {
        await navigator.share({ url });
        window._mtm.push({
            event: "Web Share",
            action: "Share Successful",
            label: url,
        });
    } catch (err) {
        window._mtm.push({
            event: "Web Share",
            action: "Share Cancelled",
            label: err.name,
        });
    }
}

function sharePage(ev) {
    ev.preventDefault();
    ev.stopImmediatePropagation();

    let url = document.location.href;
    const canonicalEl = document.querySelector("link[rel=canonical]");
    if (canonicalEl) {
        url = canonicalEl.href;
    }

    share(url);
}

function attachShare() {
    const shareLinkTriggerList = document.querySelectorAll(
        ".wp-block-button.share-link"
    );

    if (navigator.share) {
        const shareTrigger = document.querySelector(
            ".htr-modal-trigger[data-modal-id=share]"
        );
        if (shareTrigger && !shareTrigger.dataset.shareEnhanced) {
            shareTrigger.addEventListener("click", sharePage, {
                capture: true,
            });

            shareTrigger.dataset.shareEnhanced = true;
        }

        for (const shareLinkTrigger of shareLinkTriggerList) {
            const section =
                shareLinkTrigger.closest(".wp-block-buttons").parentNode;
            const link = section.querySelector("a[href]");
            if (link && !link.dataset.shareEnhanced) {
                shareLinkTrigger.addEventListener("click", (ev) => {
                    ev.preventDefault();
                    share(link.getAttribute("href"));
                });

                link.dataset.shareEnhanced = true;
            }
        }
    } else {
        for (const shareLinkTrigger of shareLinkTriggerList) {
            shareLinkTrigger.parentNode.removeChild(shareLinkTrigger);
        }
    }
}

document.documentElement.addEventListener("jenner:load", attachShare);
