<?php

namespace Michalsn\CodeIgniterHtmx\View;

use CodeIgniter\View\ViewDecoratorInterface;

class ToolbarDecorator implements ViewDecoratorInterface
{
    public static function decorate(string $html): string
    {
        if (CI_DEBUG
            && (! is_cli() || ENVIRONMENT === 'testing')
            && ! service('request')->isHtmx()
            && config('Htmx')->toolbarDecorator
            && str_contains($html, '</head>')
            && ! str_contains($html, config('Htmx')->skipViewDecoratorsString)
            && ! str_contains($html, 'id="htmxToolbarScript"')
        ) {
            $script = sprintf(
                '<script %s id="htmxToolbarScript">%s</script>',
                csp_script_nonce(),
                file_get_contents(__DIR__ . '/toolbar_decorator.js')
            );

            $html = preg_replace(
                '/<\/head>/',
                $script . '</head>',
                $html,
                1
            );
        }

        return $html;
    }
}
