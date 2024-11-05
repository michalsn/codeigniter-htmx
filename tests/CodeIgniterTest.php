<?php

declare(strict_types=1);

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class CodeIgniterTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->resetServices();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->resetServices();
    }

    public function testIsHTMXNotSavePreviousURL(): void
    {
        // Default request behavior
        $uri     = service('uri');
        $request = service('incomingrequest');

        $uri->setPath('/')->setQuery('previous=original');

        ob_start();
        service('codeigniter', null, false)->setContext('web')->run();
        ob_get_clean();

        $this->assertArrayHasKey('_ci_previous_url', $_SESSION);
        $this->assertSame('https://example.com/index.php/?previous=original', $_SESSION['_ci_previous_url']);

        // HTMX request
        $uri->setPath('/')->setQuery('previous=htmx');
        $request->appendHeader('HX-Request', 'true');

        ob_start();
        service('codeigniter', null, false)->setContext('web')->run();
        ob_get_clean();

        $this->assertTrue($request->isHTMX());
        $this->assertArrayHasKey('_ci_previous_url', $_SESSION);
        $this->assertSame('https://example.com/index.php/?previous=original', $_SESSION['_ci_previous_url']);
    }
}
