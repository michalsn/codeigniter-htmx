<?php

namespace Michalsn\CodeIgniterHtmx;

use CodeIgniter\CodeIgniter as BaseCodeIgniter;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\DownloadResponse;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\URI;
use Michalsn\CodeIgniterHtmx\HTTP\IncomingRequest as HTMXIncomingRequest;

/**
 * @property CLIRequest|HTMXIncomingRequest|IncomingRequest|null $request
 */
class CodeIgniter extends BaseCodeIgniter
{
    /**
     * Web access?
     */
    private function isWeb(): bool
    {
        return $this->context === 'web';
    }

    /**
     * If we have a session object to use, store the current URI
     * as the previous URI. This is called just prior to sending the
     * response to the client, and will make it available next request.
     *
     * This helps provider safer, more reliable previous_url() detection.
     *
     * @param string|URI $uri
     *
     * @return void
     */
    public function storePreviousURL($uri)
    {
        // Ignore CLI requests
        if (! $this->isWeb()) {
            return;
        }

        // Ignore AJAX and HTMX requests
        if ((method_exists($this->request, 'isHTMX') && $this->request->isHTMX()) || (method_exists($this->request, 'isAJAX') && $this->request->isAJAX())) {
            return;
        }

        // Ignore unroutable responses
        if ($this->response instanceof DownloadResponse || $this->response instanceof RedirectResponse) {
            return;
        }

        // Ignore non-HTML responses
        if (! str_contains($this->response->getHeaderLine('Content-Type'), 'text/html')) {
            return;
        }

        // This is mainly needed during testing...
        if (is_string($uri)) {
            $uri = service('uri', $uri, false);
        }

        if (isset($_SESSION)) {
            session()->set('_ci_previous_url', URI::createURIString(
                $uri->getScheme(),
                $uri->getAuthority(),
                $uri->getPath(),
                $uri->getQuery(),
                $uri->getFragment()
            ));
        }
    }
}
