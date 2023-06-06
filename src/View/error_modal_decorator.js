if (typeof window.htmx !== 'undefined') {
    htmx.on('htmx:responseError', function (event) {
        const xhr = event.detail.xhr;

        event.stopPropagation();

        // Create modal
        const htmxErrorModal = document.createElement('div');
        htmxErrorModal.id = 'htmxErrorModal'
        // Set title
        const htmxErrorModalTitle = document.createElement('h2');
        const htmxErrorModalTitleContent = document.createTextNode('Error: ' + xhr.status);
        htmxErrorModalTitle.appendChild(htmxErrorModalTitleContent);

        // Set close buton
        const htmxErrorModalCloseButton = document.createElement('button');
        const htmxErrorModalCloseButtonContent = document.createTextNode('X');
        htmxErrorModalCloseButton.appendChild(htmxErrorModalCloseButtonContent);
        htmxErrorModalCloseButton.id = 'htmxErrorModalCloseButton';

        // Set error content
        const htmxErrorModalContent = document.createElement('textarea');
        htmxErrorModalContent.innerHTML = xhr.response;

        // Set styles
        htmxErrorModal.setAttribute('style', 'position: absolute; max-width: 90%; left: 50%; transform: translateX(-50%); z-index: 99999; background: #fbe0e0; padding: 20px; border-radius: 5px; font-family: sans-serif; top: 50px;');
        htmxErrorModalTitle.setAttribute('style', 'display: inline-block;')
        htmxErrorModalCloseButton.setAttribute('style', 'border: 1px solid; padding: 5px 8px 3px 8px; display: inline-block; float: right;');
        htmxErrorModalContent.setAttribute('style', 'border: 1px solid #ccc; width: 80vw; height: 80vh');

        // Append content to modal
        htmxErrorModal.appendChild(htmxErrorModalTitle);
        htmxErrorModal.appendChild(htmxErrorModalCloseButton);
        htmxErrorModal.appendChild(htmxErrorModalContent);

        // Add modal to DOM
        document.body.appendChild(htmxErrorModal);

        // Handle close button
        htmxErrorModalCloseButton.onclick = function remove() {
            htmxErrorModal.parentElement.removeChild(htmxErrorModal);
        }
    });
}
