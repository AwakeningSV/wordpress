(function (window, document, $) {

    var layout   = document.getElementById('container'),
        menu     = document.getElementById('menu'),
        menuLink = document.getElementById('menuLink');

    function toggleClass(element, className) {
        var classes = element.className.split(/\s+/),
            length = classes.length,
            i = 0;

        for(; i < length; i++) {
          if (classes[i] === className) {
            classes.splice(i, 1);
            break;
          }
        }
        // The className is not found
        if (length === classes.length) {
            classes.push(className);
        }

        element.className = classes.join(' ');
    }

    menuLink.onclick = function (e) {
        var active = 'active';

        e.preventDefault();
        toggleClass(layout, active);
        toggleClass(menu, active);
        toggleClass(menuLink, active);
    };

    $('#menu a + .sub-menu').each(function (i, subMenu) {
        var a,
            li,
            timeout,
            menu = $('#menu'),
            OPEN = 'sub-menu-open';

        subMenu = $(subMenu);
        a = subMenu.prev();

        if (!a) return;

        li = a.parent();

        function transitionMenu(ev) {

            if (timeout) {
                clearTimeout(timeout);
                timeout = null;
            }

            var removeTime = ev.type === 'click' ? 0 : 350;

            if (ev.relatedTarget && menu.has(ev.relatedTarget).size()) {
                removeTime = 0;
            }

            if (!subMenu.hasClass(OPEN)) {
                ev.preventDefault();
                subMenu.addClass(OPEN);
            } else if (
                ev.type === 'mouseleave' &&
                !li.has(ev.relatedTarget).size() // mouseleave outside this li
            ) {
                timeout = setTimeout(function () {
                    subMenu.removeClass(OPEN);
                }, removeTime);
            }
        }

        li.delegate('a, .sub-menu', 'click', transitionMenu);
        li.delegate('a, .sub-menu', 'hover', transitionMenu);
    });

}(this, this.document, this.jQuery));
