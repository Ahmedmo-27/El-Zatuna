window.ElzatunaUI = window.ElzatunaUI || {};

window.ElzatunaUI.initMobileMenu = function () {
    var openBtn = document.getElementById('mobileMenuButton');
    var closeBtn = document.getElementById('mobileMenuClose');
    var overlay = document.getElementById('mobileMenuOverlay');
    var panel = document.getElementById('mobileMenuPanel');

    if (!openBtn || !closeBtn || !overlay || !panel) {
        return;
    }

    var focusableSelectors = 'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])';
    var previousFocusedElement = null;
    var overlayHideTimeoutId = null;

    var getFocusableElements = function () {
        return Array.prototype.slice.call(panel.querySelectorAll(focusableSelectors));
    };

    var setOpenState = function (isOpen) {
        openBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        panel.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        overlay.setAttribute('aria-hidden', isOpen ? 'false' : 'true');

        if (isOpen) {
            document.body.classList.add('overflow-hidden');
        } else {
            document.body.classList.remove('overflow-hidden');
        }
    };

    var trapFocus = function (event) {
        if (event.key !== 'Tab') {
            return;
        }

        var focusableElements = getFocusableElements();

        if (!focusableElements.length) {
            event.preventDefault();
            return;
        }

        var firstElement = focusableElements[0];
        var lastElement = focusableElements[focusableElements.length - 1];

        if (event.shiftKey && document.activeElement === firstElement) {
            event.preventDefault();
            lastElement.focus();
        } else if (!event.shiftKey && document.activeElement === lastElement) {
            event.preventDefault();
            firstElement.focus();
        }
    };

    var handleKeydown = function (event) {
        if (event.key === 'Escape') {
            closeMenu();
            return;
        }

        trapFocus(event);
    };

    var openMenu = function () {
        previousFocusedElement = document.activeElement;

        if (overlayHideTimeoutId) {
            clearTimeout(overlayHideTimeoutId);
            overlayHideTimeoutId = null;
        }

        overlay.classList.remove('hidden');

        requestAnimationFrame(function () {
            overlay.classList.remove('opacity-0');
            overlay.classList.add('opacity-100');
            panel.classList.remove('translate-x-full');
            setOpenState(true);

            var focusableElements = getFocusableElements();
            if (focusableElements.length) {
                focusableElements[0].focus();
            } else {
                panel.focus();
            }
        });

        document.addEventListener('keydown', handleKeydown);
    };

    var closeMenu = function () {
        if (overlayHideTimeoutId) {
            clearTimeout(overlayHideTimeoutId);
            overlayHideTimeoutId = null;
        }

        overlay.classList.add('opacity-0');
        overlay.classList.remove('opacity-100');
        panel.classList.add('translate-x-full');
        setOpenState(false);

        document.removeEventListener('keydown', handleKeydown);

        overlayHideTimeoutId = setTimeout(function () {
            overlay.classList.add('hidden');
            overlayHideTimeoutId = null;
        }, 300);

        if (previousFocusedElement && typeof previousFocusedElement.focus === 'function') {
            previousFocusedElement.focus();
        }
    };

    openBtn.addEventListener('click', openMenu);
    closeBtn.addEventListener('click', closeMenu);
    overlay.addEventListener('click', closeMenu);

    panel.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });
};
