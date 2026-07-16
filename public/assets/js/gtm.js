(function () {
    "use strict";

    const containerId = document.currentScript ? document.currentScript.dataset.gtmId : null;
    if (!containerId) {
        return;
    }

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({ "gtm.start": new Date().getTime(), event: "gtm.js" });

    const gtmLoader = document.createElement("script");
    gtmLoader.async = true;
    gtmLoader.src = "https://www.googletagmanager.com/gtm.js?id=" + encodeURIComponent(containerId);
    document.head.appendChild(gtmLoader);
})();
