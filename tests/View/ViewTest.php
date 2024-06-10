<?php

namespace Tests\View;

use CodeIgniter\Autoloader\FileLocatorInterface;
use CodeIgniter\Config\Services;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\View\Exceptions\ViewException;
use Config\View as ViewConfig;
use Michalsn\CodeIgniterHtmx\View\View;
use RuntimeException;

/**
 * @internal
 */
final class ViewTest extends CIUnitTestCase
{
    private FileLocatorInterface $loader;
    private string $viewsDir;
    private ViewConfig $config;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resetServices();
        $this->loader   = Services::locator();
        $this->viewsDir = SUPPORTPATH . 'Views';
        $this->config   = new ViewConfig();
    }

    public function testRenderViewData(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Fragment 1 header\nHello World, fragment1!\nFragment 2 header\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_fragment'));
    }

    public function testRenderViewDataWithDebug(): void
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

    public function testRenderViewFragment(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1']]));
    }

    public function testRenderViewFragmentInViewFragment(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_fragment_in_view_fragment', ['fragments' => ['sample2']]));
    }

    public function testRenderViewFragments(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1', 'sample2']]));
    }

    public function testRenderViewFragmentsWithInclude(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $expected = "Hello World, fragment1!\nview included\n";

        $this->assertSame($expected, $view->render('view_with_include_1', ['fragments' => ['sample1']]));
    }

    public function testRenderViewFragmentsFromInclude(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment2!\n";

        $this->assertSame($expected, $view->render('view_with_include_2', ['fragments' => ['sample2']]));
    }

    public function testRenderDefaultInclude(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $expected = "view included\n";

        $this->assertSame($expected, $view->render('default_include'));
    }

    public function testRenderViewDataWithLayout(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Page top\n\nFragment 1 header\nHello World, fragment1!\nFragment 2 header\nHello World, fragment2!\n\nPage bottom";

        $this->assertSame($expected, $view->render('with_fragment'));
    }

    public function testRenderViewFragmentWithLayout(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample1']]));
    }

    public function testRenderViewFragmentsWithLayout(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample1', 'sample2']]));
    }

    public function testRenderViewFragmentFromLayout(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $expected = 'Page bottom';

        $this->assertSame($expected, $view->render('with_fragment', ['fragments' => ['sample0']]));
    }

    public function testRenderViewFragmentDoesntExists(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = '';

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sampleX']]));
    }

    public function testRenderViewFragmentBroken(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $expected = '';

        $this->expectException(RuntimeException::class);
        $this->assertStringContainsString($expected, $view->render('view_fragment_error', ['fragments' => ['broken']]));
    }

    public function testRenderViewFragmentWithCache(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $view->setVar('testString1', 'Hello World');
        $view->setVar('testString2', 'Hello World');
        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1'], 'cache' => 10]));
        // this second renderings should go through the cache
        $this->assertSame($expected, $view->render('view_fragment', ['fragments' => ['sample1'], 'cache' => 10]));
    }

    public function testRendersThrowsExceptionIfFileNotFound(): void
    {
        $view = new View($this->config, $this->viewsDir, $this->loader);

        $this->expectException(ViewException::class);
        $view->setVar('testString', 'Hello World');

        $view->render('missing');
    }
}
