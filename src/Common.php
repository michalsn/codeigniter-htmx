<?php

use CodeIgniter\Config\Services;
use CodeIgniter\Config\View;

if (! function_exists('view_fragment')) {
    /**
     * Grabs the current RendererInterface-compatible class
     * and tells it to render the specified view fragments.
     * Simply provides a convenience method that can be used
     * in Controllers, libraries, and routed closures.
     *
     * NOTE: Does not provide any escaping of the data, so that must
     * all be handled manually by the developer.
     *
     * @param array $options Options for saveData or third-party extensions.
     */
    function view_fragment(string $name, string|array $fragments, array $data = [], array $options = []): string
    {
        $renderer = Services::renderer();

        /** @var View $config */
        $config   = config(View::class);
        $saveData = $config->saveData;

        if (array_key_exists('saveData', $options)) {
            $saveData = (bool) $options['saveData'];
            unset($options['saveData']);
        }

        $options['fragments'] = is_string($fragments)
            ? array_map('trim', explode(',', $fragments))
            : $fragments;

        return $renderer->setData($data, 'raw')->render($name, $options, $saveData);
    }
}
