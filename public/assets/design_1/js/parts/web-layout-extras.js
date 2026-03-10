window.ElzatunaUI = window.ElzatunaUI || {};

window.ElzatunaUI.initPageLoader = function () {
    var loaderHasBeenHidden = false;

    var hideLoader = function () {
        if (loaderHasBeenHidden) {
            return;
        }

        var loader = document.getElementById('pageLoader');

        loaderHasBeenHidden = true;

        if (loader) {
            loader.classList.add('hidden');

            setTimeout(function () {
                if (loader && loader.parentNode) {
                    loader.parentNode.removeChild(loader);
                }
            }, 260);
        }
    };

    if (document.readyState === 'complete') {
        hideLoader();
    }

    window.addEventListener('load', hideLoader);

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(hideLoader, 150);
        });
    } else {
        setTimeout(hideLoader, 150);
    }

    setTimeout(hideLoader, 4000);
};
