if (typeof window.htmx !== 'undefined' &&
    typeof window.loadDoc !== 'undefined' &&
    document.getElementById('debugbar_dynamic_script')) {
    let lastDebugBarTime = null;

    const getDebugBarTime = function (event) {
        const detail = event.detail || {};
        const response = detail.ctx && detail.ctx.response ? detail.ctx.response : null;

        if (response !== null && response.headers && typeof response.headers.get === 'function') {
            return response.headers.get('debugbar-time');
        }

        return null;
    };

    const refreshDebugBar = function (event) {
        const debugBarTime = getDebugBarTime(event);

        if (debugBarTime !== null && debugBarTime !== lastDebugBarTime) {
            lastDebugBarTime = debugBarTime;
            loadDoc(debugBarTime);
        }
    };

    htmx.on('htmx:finally:request', refreshDebugBar);
}
