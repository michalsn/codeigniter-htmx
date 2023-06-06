<?php

namespace Michalsn\CodeIgniterHtmx\Config;

use Michalsn\CodeIgniterHtmx\View\ErrorModalDecorator;
use Michalsn\CodeIgniterHtmx\View\ToolbarDecorator;

class Registrar
{
    public static function View(): array
    {
        return [
            'decorators' => [
                ToolbarDecorator::class,
                ErrorModalDecorator::class,
            ],
        ];
    }
}
