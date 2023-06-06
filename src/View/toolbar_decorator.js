if (typeof window.htmx !== 'undefined' &&
    typeof window.loadDoc !== 'undefined' &&
    document.getElementById('debugbar_dynamic_script')) {
    htmx.on('htmx:afterSettle', function (event) {
        let debugBarTime = event.detail.xhr.getResponseHeader('debugbar-time');
        if (debugBarTime !== null) {
            loadDoc(debugBarTime);
        }
    });
}
