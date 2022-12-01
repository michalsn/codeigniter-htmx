<?php

namespace Tests\View;

use CodeIgniter\Config\Services;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\View\Exceptions\ViewException;

use Config;

use Michalsn\CodeIgniterHtmx\View\View;
use RuntimeException;

/**
 * @internal
 */
final class ViewTest extends CIUnitTestCase
{
    private $loader;
    private $viewsDir;
    private $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetServices();
        $this->loader   = Services::locator();
        $this->viewsDir = __DIR__ . '/../_support/Views';
        $this->config   = new Config\View();
    }

    public function testRenderViewData()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Fragment 1 header\nHello World, fragment1!\nFragment 2 header\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_fragment'));
    }

    public function testRenderViewDataWithDebug()
    {
        service('filters')->enableFilters(['toolbar'], 'after');

        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Fragment 1 header\nHello World, fragment1!\nFragment 2 header\nHello World, fragment2!\n";

        $result = $view->render('view_fragment');
        $this->assertStringContainsString($expected, $result);
        $this->assertStringContainsString('<!-- DEBUG-VIEW START 1', $result);
        $this->assertStringContainsString('<!-- DEBUG-VIEW ENDED 1', $result);
    }

    public function testRenderViewFragment()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1']]));
    }

    public function testRenderViewFragments()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1', 'sample2']]));
    }

    public function testRenderViewDataWithLayout()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Page top\n\nFragment 1 header\nHello World, fragment1!\nFragment 2 header\nHello World, fragment2!\n\nPage bottom";

        $this->assertSame($expected, $view->render('with_fragment'));
    }

    public function testRenderViewFragmentWithLayout()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample1']]));
    }

    public function testRenderViewFragmentsWithLayout()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample1', 'sample2']]));
    }

    public function testRenderViewFragmentFromLayout()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $expected = "Page bottom";

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample0']]));
    }

    public function testRenderViewFragmentDoesntExists()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sampleX']]));
    }

    public function testRenderViewFragmentBroken()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $expected = '';

        $this->expectException(RuntimeException::class);
        $this->assertStringContainsString($expected, $view->render('view_fragment_error', ['fragments' => ['broken']]));
    }

    public function testRenderViewFragmentWithCache()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1'], 'cache' => 10]));
        // this second renderings should go through the cache
        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1'], 'cache' => 10]));
    }

    public function testRendersThrowsExceptionIfFileNotFound()
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $this->expectException(ViewException::class);
        $view->setVar('testString', 'Hello World');

        $view->render('missing');
    }
}
