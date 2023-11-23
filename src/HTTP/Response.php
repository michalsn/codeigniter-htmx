<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use CodeIgniter\HTTP\Response as BaseResponse;
use InvalidArgumentException;

class Response extends BaseResponse
{
    use HtmxTrait;

    /**
     * Pushes a new url into the history stack.
     *
     * @return Response;
     */
    public function setPushUrl(?string $url = null): Response
    {
        $this->setHeader('HX-Push-Url', $url ?? 'false');

        return $this;
    }

    /**
     * Replaces the current URL in the location bar.
     *
     * @return Response;
     */
    public function setReplaceUrl(?string $url = null): Response
    {
        $this->setHeader('HX-Replace-Url', $url ?? 'false');

        return $this;
    }

    /**
     * Allows you to specify how the response will be swapped.
     *
     * @return Response;
     */
    public function setReswap(string $method): Response
    {
        $this->validateSwap($method, 'HX-Reswap');

        $this->setHeader('HX-Reswap', $method);

        return $this;
    }

    /**
     * A CSS selector that updates the target of the content
     * update to a different element on the page.
     *
     * @return Response;
     */
    public function setRetarget(string $selector): Response
    {
        $this->setHeader('HX-Retarget', $selector);

        return $this;
    }

    /**
     * A CSS selector that allows you to choose which part
     * of the response is used to be swapped in.
     *
     * @return Response;
     */
    public function setReselect(string $selector): Response
    {
        $this->setHeader('HX-Reselect', $selector);

        return $this;
    }

    /**
     * Allows you to trigger client side events.
     *
     * @return Response;
     */
    public function triggerClientEvent(string $name, array|string $params = '', string $after = 'receive'): Response
    {
        $header = match ($after) {
            'receive' => 'HX-Trigger',
            'settle'  => 'HX-Trigger-After-Settle',
            'swap'    => 'HX-Trigger-After-Swap',
            default   => throw new InvalidArgumentException('A value for "after" argument must be one of: "receive", "settle", or "swap".'),
        };

        if ($this->hasHeader($header)) {
            $data = json_decode($this->header($header)->getValue(), true);
            if ($data === null) {
                throw new InvalidArgumentException(sprintf('%s header value should be a valid JSON.', $header));
            }
            $data[$name] = $params;
        } else {
            $data = [$name => $params];
        }

        $this->setHeader($header, json_encode($data));

        return $this;
    }
}
