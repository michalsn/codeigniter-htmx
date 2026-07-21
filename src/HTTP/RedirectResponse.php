<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use CodeIgniter\HTTP\RedirectResponse as BaseRedirectResponse;

class RedirectResponse extends BaseRedirectResponse
{
    use HtmxTrait;

    /**
     * Sets the HX-Location to redirect
     * without reloading the whole page.
     *
     * @var string  - Path is required and is url to load the response from
     * @var ?string - the source element of the request
     * @var ?string - an event that “triggered” the request
     * @var ?string - a callback that will handle the response HTML
     * @var ?string - the target to swap the response into
     * @var ?string - how the response will be swapped in relative to the target
     * @var ?string - values to submit with the request
     * @var ?string - headers to submit with the request
     * @var ?string - allows you to select the content you want swapped from a response
     * @var ?string - set to 'false' or a path string to prevent or override the URL pushed to browser location history
     * @var ?string - a path string to replace the URL in the browser location history
     */
    public function hxLocation(
        string $path,
        ?string $source = null,
        ?string $event = null,
        ?string $target = null,
        ?string $swap = null,
        ?array $values = null,
        ?array $headers = null,
        ?string $push = null,
        ?string $replace = null,
        ?string $select = null,
        ?string $handler = null,
    ): RedirectResponse {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $path = (string) service('uri', $path, false)->withScheme('')->setHost('');
        }

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

        if ($push !== null) {
            $data['push'] = $push;
        }

        if ($replace !== null) {
            $data['replace'] = $replace;
        }

        if ($select !== null) {
            $data['select'] = $select;
        }

        if ($handler !== null) {
            $data['handler'] = $handler;
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
