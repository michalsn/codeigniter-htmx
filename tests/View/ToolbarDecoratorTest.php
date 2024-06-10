<?php

namespace Tests\View;

use CodeIgniter\Autoloader\FileLocatorInterface;
use CodeIgniter\Config\Factories;
use CodeIgniter\Config\Services;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\View\View;
use Config\View as ViewConfig;
use Michalsn\CodeIgniterHtmx\Config\Htmx as HtmxConfig;
use Michalsn\CodeIgniterHtmx\View\ToolbarDecorator;

/**
 * @internal
 */
final class ToolbarDecoratorTest extends CIUnitTestCase
{
    private FileLocatorInterface $loader;
    private string $viewsDir;
    private ViewConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader   = Services::locator();
        $this->viewsDir = SUPPORTPATH . 'Views';
        $this->config   = new ViewConfig();
    }

    public function testDecoratorDontApply(): void
    {
        $config             = $this->config;
        $config->decorators = [ToolbarDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'Hello World');
        $expected1 = '<h1>Hello World</h1>';
        $expected2 = 'id="htmxToolbarScript"';

        $this->assertStringContainsString($expected1, $view->render('without_decorator'));
        $this->assertStringNotContainsString($expected2, $view->render('without_decorator'));
    }

    public function testDecoratorApply(): void
    {
        $config             = $this->config;
        $config->decorators = [ToolbarDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'Hello World');
        $expected1 = '</script></head><body>Hello World';
        $expected2 = 'id="htmxToolbarScript"';

        $this->assertStringContainsString($expected1, $view->render('with_decorator'));
        $this->assertStringContainsString($expected2, $view->render('with_decorator'));
    }

    public function testDecoratorDisabled(): void
    {
        $htmxConfig                   = new HtmxConfig();
        $htmxConfig->toolbarDecorator = false;
        Factories::injectMock('config', 'Htmx', $htmxConfig);

        $config             = $this->config;
        $config->decorators = [ToolbarDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'Hello World');
        $expected1 = '<h1>Hello World</h1>';
        $expected2 = 'id="htmxToolbarScript"';

        $this->assertStringContainsString($expected1, $view->render('without_decorator'));
        $this->assertStringNotContainsString($expected2, $view->render('without_decorator'));
    }

    public function testDecoratorDisabledWithSkipDecoratorsString(): void
    {
        $config             = $this->config;
        $config->decorators = [ToolbarDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'htmxSkipViewDecorators');
        $expected1 = '<h1>htmxSkipViewDecorators</h1>';
        $expected2 = 'id="htmxToolbarScript"';

        $this->assertStringContainsString($expected1, $view->render('without_decorator'));
        $this->assertStringNotContainsString($expected2, $view->render('without_decorator'));
    }
}
