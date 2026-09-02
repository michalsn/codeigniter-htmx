<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use CodeIgniter\HTTP\IncomingRequest as BaseIncomingRequest;

class IncomingRequest extends BaseIncomingRequest
{
    /**
     * Indicates that the request is triggered by Htmx.
     *
     * Checks whether the request carries the HX-Request header
     * with the value "true".
     */
    public function isHtmx(): bool
    {
        return $this->getHtmxHeaderToBool('HX-Request');
    }

    /**
     * Indicates that the request is via an element using hx-boost.
     */
    public function isBoosted(): bool
    {
        return $this->getHtmxHeaderToBool('HX-Boosted');
    }

    /**
     * True if the request is for history restoration.
     */
    public function isHistoryRestoreRequest(): bool
    {
        return $this->getHtmxHeaderToBool('HX-History-Restore-Request');
    }

    /**
     * The request type for HTMX 4 requests.
     *
     * Returns "partial" for targeted swaps and "full"
     * for body-level swaps, including hx-boost requests,
     * or requests using hx-select.
     */
    public function getRequestType(): ?string
    {
        return $this->getHtmxHeader('HX-Request-Type');
    }

    /**
     * Indicates a partial HTMX request.
     */
    public function isPartial(): bool
    {
        return $this->getRequestType() === 'partial';
    }

    /**
     * Indicates a full HTMX request.
     */
    public function isFull(): bool
    {
        return $this->getRequestType() === 'full';
    }

    /**
     * The current URL of the browser.
     */
    public function getCurrentUrl(): ?string
    {
        return $this->getHtmxHeader('HX-Current-Url');
    }

    /**
     * The prompt response sent by the optional hx-prompt extension.
     */
    public function getPrompt(): ?string
    {
        return $this->getHtmxHeader('HX-Prompt');
    }

    /**
     * The identifier of the triggered element if it exists.
     *
     * In HTMX 4 this is sent in HX-Source and uses the
     * element identifier format, such as "button#submit".
     */
    public function getSource(): ?string
    {
        return $this->getHtmxHeader('HX-Source');
    }

    /**
     * The identifier of the target element if it exists.
     *
     * HTMX 4 uses the same element identifier format as HX-Source,
     * for example "div#results".
     */
    public function getTarget(): ?string
    {
        return $this->getHtmxHeader('HX-Target');
    }

    /**
     * Helper method to get the Htmx header value
     */
    private function getHtmxHeader(string $header): ?string
    {
        if (! $this->hasHeader($header)) {
            return null;
        }

        return $this->header($header)->getValueLine();
    }

    /**
     * Helper method to cast Htmx header to bool
     */
    private function getHtmxHeaderToBool(string $header): bool
    {
        return $this->hasHeader($header)
            && $this->header($header)->getValueLine() === 'true';
    }

    /**
     * Checks this request type.
     *
     * @param         string                                                                                                      $type HTTP verb or 'json' or 'ajax' or 'htmx' or 'boosted' or 'partial' or 'full'
     * @phpstan-param string|'get'|'post'|'put'|'delete'|'head'|'patch'|'options'|'json'|'ajax'|'htmx'|'boosted'|'partial'|'full' $type
     */
    public function is(string $type): bool
    {
        $valueUpper = strtoupper($type);

        if ($valueUpper === 'HTMX') {
            return $this->isHtmx();
        }

        if ($valueUpper === 'BOOSTED') {
            return $this->isBoosted();
        }

        if ($valueUpper === 'PARTIAL') {
            return $this->isPartial();
        }

        if ($valueUpper === 'FULL') {
            return $this->isFull();
        }

        return parent::is($type);
    }
}
