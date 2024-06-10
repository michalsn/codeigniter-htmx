<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use CodeIgniter\HTTP\RedirectResponse as BaseRedirectResponse;

class RedirectResponse extends BaseRedirectResponse
{
    use HtmxTrait;

    /**
     * Sets the HX-Location to redirect
     * without reloading the whole page.
     */
    public function hxLocation(
        string $path,
        ?string $source = null,
        ?string $event = null,
        ?string $target = null,
        ?string $swap = null,
        ?array $values = null,
        ?array $headers = null
    ): RedirectResponse {
        $data = ['path' => '/' . ltrim($path, '/')];

        if ($source !== null) {
            $data['source'] = $source;
        }

        if ($event !== null) {
            $data['event'] = $event;
        }

        if ($target !== null) {
            $data['target'] = $target;
        }

        if ($swap !== null) {
            $this->validateSwap($swap);
            $data['swap'] = $swap;
        }

        if ($values !== null && $values !== []) {
            $data['values'] = $values;
        }

        if ($headers !== null && $headers !== []) {
            $data['headers'] = $headers;
        }

        return $this->setStatusCode(200)->setHeader('HX-Location', json_encode($data));
    }

    /**
     * Sets the HX-Redirect to URI to redirect to.
     *
     * @param string $uri The URI to redirect to
     */
    public function hxRedirect(string $uri): RedirectResponse
    {
        if (! str_starts_with($uri, 'http')) {
            $uri = site_url($uri);
        }

        return $this->setStatusCode(200)->setHeader('HX-Redirect', $uri);
    }

    /**
     * Sets the HX-Refresh to true.
     */
    public function hxRefresh(): RedirectResponse
    {
        return $this->setStatusCode(200)->setHeader('HX-Refresh', 'true');
    }
}
