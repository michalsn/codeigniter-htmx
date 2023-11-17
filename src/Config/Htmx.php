<?php

namespace Michalsn\CodeIgniterHtmx\Config;

use CodeIgniter\Config\BaseConfig;

class Htmx extends BaseConfig
{
    /**
     * Enable / disable ToolbarDecorator.
     */
    public bool $toolbarDecorator = true;

    /**
     * Enable / diable ErrorModalDecorator.
     */
    public bool $errorModalDecorator = true;

    /**
     * The appearance of this string in the view
     * content will skip the htmx decorators. Even
     * when they are enabled.
     */
    public string $skipViewDecoratorsString = 'htmxSkipViewDecorators';
}
