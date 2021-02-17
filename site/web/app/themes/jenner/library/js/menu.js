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
            OPEN = 'sub-menu-open';

        subMenu = $(subMenu);
        a = subMenu.prev();

        if (!a) return;

        li = a.parent();

        function transitionMenu(ev) {
            if (!subMenu.hasClass(OPEN)) {
                ev.preventDefault();
                $('.sub-menu-open').each(function (j, otherMenu) {
                    $(otherMenu).removeClass(OPEN);
                });
                subMenu.addClass(OPEN);
            }
        }

        li.delegate('a, .sub-menu', 'click', transitionMenu);
    });

}(this, this.document, this.jQuery));
