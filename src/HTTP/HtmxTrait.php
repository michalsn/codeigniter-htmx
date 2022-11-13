<?php

namespace Michalsn\CodeIgniterHtmx\HTTP;

use InvalidArgumentException;

trait HtmxTrait
{
    private array $swapOptions = [
        'innerHTML',
        'outerHTML',
        'beforebegin',
        'afterbegin',
        'beforeend',
        'afterend',
        'delete',
        'none',
    ];

    /**
     * Validate swap option
     */
    private function validateSwap(string $option, string $field = 'swap'): bool
    {
        [$option] = explode(' ', $option, 2);

        if (! in_array($option, $this->swapOptions, true)) {
            throw new InvalidArgumentException(sprintf(
                'Option "%s" is not a valid variable for %s. A valid option has to be one of: %s',
                $option,
                $field,
                implode(', ', $this->swapOptions)
            ));
        }

        return true;
    }
}
