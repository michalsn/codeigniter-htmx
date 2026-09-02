<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use CodeIgniter\Exceptions\InvalidArgumentException;
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
        array|string|null $event = null,
        ?string $target = null,
        ?string $swap = null,
        ?array $values = null,
        ?array $headers = null,
        false|string|null $push = null,
        false|string|null $replace = null,
        ?string $select = null,
        ?string $handler = null,
        ?string $selectOOB = null,
        ?bool $transition = null,
    ): RedirectResponse {
        if ($handler !== null) {
            throw new InvalidArgumentException(
                'The "handler" option is not supported by htmx 4. Use client-side htmx lifecycle events instead.',
            );
        }

        $data = ['path' => $this->normalizeLocationPath($path)];

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
            $data['push'] = $this->normalizeHistoryOption($push);
        }

        if ($replace !== null) {
            $data['replace'] = $this->normalizeHistoryOption($replace);
        }

        if ($select !== null) {
            $data['select'] = $select;
        }

        if ($selectOOB !== null) {
            $data['selectOOB'] = $selectOOB;
        }

        if ($transition !== null) {
            $data['transition'] = $transition;
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

    private function normalizeLocationPath(string $path): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $path = (string) service('uri', $path, false)->withScheme('')->setHost('');
        }

        return '/' . ltrim($path, '/');
    }

    private function normalizeHistoryOption(false|string $option): false|string
    {
        if ($option === false || $option === 'true' || $option === 'false') {
            return $option;
        }

        return $this->normalizeLocationPath($option);
    }
}
