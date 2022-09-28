document.addEventListener('DOMContentLoaded', () => {
    const teachingSearch = document.querySelector('.teaching-header .wp-block-search');

    if (teachingSearch) {
        const input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'post_type');
        input.setAttribute('value', 'teaching');
        teachingSearch.appendChild(input);
    }
})