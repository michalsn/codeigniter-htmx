if (typeof window.htmx !== 'undefined') {
    const handledResponses = new WeakSet();

    const isHtmlResponse = function (body, contentType) {
        if (typeof contentType === 'string') {
            const normalizedContentType = contentType.toLowerCase();

            if (
                normalizedContentType.includes('text/html')
                || normalizedContentType.includes('application/xhtml+xml')
            ) {
                return true;
            }
        }

        const trimmedBody = body.trim().toLowerCase();

        return trimmedBody.startsWith('<!doctype html')
            || trimmedBody.startsWith('<html')
            || trimmedBody.startsWith('<head')
            || trimmedBody.startsWith('<body');
    };

    const getElements = function () {
        let modal = document.getElementById('htmxErrorModal');
        let title = document.getElementById('htmxErrorModalTitle');
        let previewButton = document.getElementById('htmxErrorModalPreviewButton');
        let sourceButton = document.getElementById('htmxErrorModalSourceButton');
        let preview = document.getElementById('htmxErrorModalPreview');
        let iframe = document.getElementById('htmxErrorModalIframe');
        let content = document.getElementById('htmxErrorModalContent');

        if (modal === null) {
            modal = document.createElement('div');
            modal.id = 'htmxErrorModal';
            modal.setAttribute('style', 'position: fixed; width: min(96vw, 1100px); max-height: calc(100vh - 100px); left: 50%; transform: translateX(-50%); z-index: 99999; background: #fbe0e0; padding: 20px; border-radius: 5px; font-family: sans-serif; top: 50px; overflow: hidden; box-sizing: border-box; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);');

            title = document.createElement('h2');
            title.id = 'htmxErrorModalTitle';
            title.setAttribute('style', 'display: inline-block;');

            const closeButton = document.createElement('button');
            closeButton.id = 'htmxErrorModalCloseButton';
            closeButton.textContent = 'X';
            closeButton.setAttribute('style', 'border: 1px solid; padding: 5px 8px 3px 8px; display: inline-block; float: right;');

            const controls = document.createElement('div');
            controls.setAttribute('style', 'display: flex; gap: 8px; margin: 16px 0 12px;');

            previewButton = document.createElement('button');
            previewButton.id = 'htmxErrorModalPreviewButton';
            previewButton.textContent = 'Preview';
            previewButton.setAttribute('type', 'button');
            previewButton.setAttribute('style', 'border: 1px solid #999; background: #fff; padding: 6px 10px;');

            sourceButton = document.createElement('button');
            sourceButton.id = 'htmxErrorModalSourceButton';
            sourceButton.textContent = 'Source';
            sourceButton.setAttribute('type', 'button');
            sourceButton.setAttribute('style', 'border: 1px solid #999; background: #fff; padding: 6px 10px;');

            controls.appendChild(previewButton);
            controls.appendChild(sourceButton);

            preview = document.createElement('div');
            preview.id = 'htmxErrorModalPreview';
            preview.setAttribute('style', 'display: none; border: 1px solid #ccc; background: #fff; height: calc(100vh - 260px); max-height: calc(100vh - 260px); box-sizing: border-box;');

            iframe = document.createElement('iframe');
            iframe.id = 'htmxErrorModalIframe';
            iframe.setAttribute('sandbox', '');
            iframe.setAttribute('style', 'display: block; border: 0; width: 100%; height: 100%; background: #fff;');
            preview.appendChild(iframe);

            content = document.createElement('textarea');
            content.id = 'htmxErrorModalContent';
            content.setAttribute('readonly', 'readonly');
            content.setAttribute('style', 'display: block; border: 1px solid #ccc; width: 100%; height: calc(100vh - 260px); max-height: calc(100vh - 260px); background: #fff; box-sizing: border-box;');

            modal.appendChild(title);
            modal.appendChild(closeButton);
            modal.appendChild(controls);
            modal.appendChild(preview);
            modal.appendChild(content);
            document.body.appendChild(modal);

            closeButton.onclick = function remove() {
                modal.parentElement.removeChild(modal);
            };
        }

        return {
            title: title,
            previewButton: previewButton,
            sourceButton: sourceButton,
            preview: preview,
            iframe: iframe,
            content: content,
        };
    };

    const setMode = function (elements, mode) {
        const isPreview = mode === 'preview';

        elements.preview.style.display = isPreview ? 'block' : 'none';
        elements.content.style.display = isPreview ? 'none' : 'block';

        elements.previewButton.style.background = isPreview ? '#d7e9ff' : '#fff';
        elements.sourceButton.style.background = isPreview ? '#fff' : '#d7e9ff';
    };

    const showModal = function (status, body, contentType) {
        const elements = getElements();
        const hasBody = body !== '';
        const canPreviewHtml = hasBody && isHtmlResponse(body, contentType);

        elements.title.textContent = 'Error: ' + status;
        elements.content.value = body === '' ? 'No response body available.' : body;

        elements.previewButton.style.display = canPreviewHtml ? 'inline-block' : 'none';
        elements.sourceButton.style.display = 'inline-block';

        if (canPreviewHtml) {
            elements.iframe.srcdoc = body;
            setMode(elements, 'preview');
        } else {
            elements.iframe.srcdoc = '';
            setMode(elements, 'source');
        }

        elements.previewButton.onclick = function () {
            setMode(elements, 'preview');
        };

        elements.sourceButton.onclick = function () {
            setMode(elements, 'source');
        };
    };

    const showFetchResponseError = function (response) {
        if (response === null || typeof response.status !== 'number' || response.status < 400) {
            return;
        }

        let contentType = '';

        if (
            response.raw
            && response.raw.headers
            && typeof response.raw.headers.get === 'function'
        ) {
            contentType = response.raw.headers.get('content-type') || '';
        }

        if (response.raw && typeof response.raw.clone === 'function') {
            handledResponses.add(response.raw);

            response.raw.clone().text()
                .then(function (body) {
                    showModal(response.status, body, contentType);
                })
                .catch(function () {
                    showModal(response.status, '', contentType);
                });

            return;
        }

        showModal(response.status, '', contentType);
    };

    // We need both hooks: one to read failed HTTP responses, and one as a
    // fallback for HTMX errors that do not expose a readable response body.
    htmx.on('htmx:before:response', function (event) {
        const detail = event.detail || {};
        const ctx = detail.ctx || {};

        if (ctx.response && typeof ctx.response.status === 'number' && ctx.response.status >= 400) {
            showFetchResponseError(ctx.response);
            event.preventDefault();
        }
    });

    htmx.on('htmx:error', function (event) {
        const detail = event.detail || {};
        const ctx = detail.ctx || {};

        if (ctx.response) {
            if (ctx.response.raw && handledResponses.has(ctx.response.raw)) {
                return;
            }

            showFetchResponseError(ctx.response);
            return;
        }

        showModal(ctx.status || 'request error', detail.error ? String(detail.error) : '', '');
    });

}
