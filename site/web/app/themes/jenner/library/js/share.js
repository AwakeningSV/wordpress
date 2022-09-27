function sharePage(ev) {
    ev.preventDefault();
    ev.stopImmediatePropagation();

    let url = document.location.href;
    const canonicalEl = document.querySelector('link[rel=canonical]');
    if (canonicalEl) {
        url = canonicalEl.href;
    }

    navigator.share({url});
}

function attachShare() {
    const shareTrigger = document.querySelector('.htr-modal-trigger[data-modal-id=share]');
    shareTrigger.addEventListener('click', sharePage, {capture: true});
}

if (navigator.share) {
    document.addEventListener('DOMContentLoaded', attachShare);
}