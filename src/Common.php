<?php

namespace Michalsn\CodeIgniterHtmx;

use CodeIgniter\Config\View;
use Michalsn\CodeIgniterHtmx\Config\Services;

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
