(function () {
    "use strict";

    const measurementId = document.currentScript ? document.currentScript.dataset.gaId : null;
    if (!measurementId) {
        return;
    }

    const gtagLoader = document.createElement("script");
    gtagLoader.async = true;
    gtagLoader.src = "https://www.googletagmanager.com/gtag/js?id=" + encodeURIComponent(measurementId);
    document.head.appendChild(gtagLoader);

    window.dataLayer = window.dataLayer || [];
    window.gtag = function () {
        dataLayer.push(arguments);
    };
    gtag("js", new Date());
    gtag("config", measurementId);
})();
