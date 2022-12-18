<?php

namespace Tests\View;

use CodeIgniter\Autoloader\FileLocator;
use CodeIgniter\Config\Factories;
use CodeIgniter\Config\Services;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\View\View;
use Config;
use Michalsn\CodeIgniterHtmx\View\ErrorModalDecorator;

/**
 * @internal
 */
final class ErrorModelDecoratorTest extends CIUnitTestCase
{
    private FileLocator $loader;
    private string $viewsDir;
    private \Config\View $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loader   = Services::locator();
        $this->viewsDir = SUPPORTPATH . 'Views';
        $this->config   = new Config\View();
    }

    public function testDecoratorDontApply()
    {
        $config             = $this->config;
        $config->decorators = [ErrorModalDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'Hello World');
        $expected1 = '<h1>Hello World</h1>';
        $expected2 = 'id="htmxErrorModalScript"';

        $this->assertStringContainsString($expected1, $view->render('without_decorator'));
        $this->assertStringNotContainsString($expected2, $view->render('without_decorator'));
    }

    public function testDecoratorApply()
    {
        $config             = $this->config;
        $config->decorators = [ErrorModalDecorator::class];
        Factories::injectMock('config', 'View', $config);

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString', 'Hello World');
        $expected1 = '<body>Hello World<script';
        $expected2 = 'id="htmxErrorModalScript"';

        $this->assertStringContainsString($expected1, $view->render('with_decorator'));
        $this->assertStringContainsString($expected2, $view->render('with_decorator'));
    }
}
