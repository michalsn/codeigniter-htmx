<?php

namespace Michalsn\CodeIgniterHtmx\Config;

class Registrar
{
    public static function View(): array
    {
        return [
            'decorators' => ['Michalsn\CodeIgniterHtmx\View\ErrorModalDecorator'],
        ];
    }
}
