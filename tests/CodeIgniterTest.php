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

    public function testStorePreviousURLIsHTMX(): void
    {
        $_SESSION['_ci_previous_url'] = 'https://example.com/index.php/?previous=original';

        config('Htmx')->storePreviousURL = true;

        service('uri')->setPath('/')->setQuery('previous=saved_from_htmx');
        service('request')->appendHeader('HX-Request', 'true');

        ob_start();
        service('codeigniter', null, false)->setContext('web')->run();
        ob_get_clean();

        $this->assertTrue(service('request')->isHTMX());
        $this->assertSame('https://example.com/index.php/?previous=saved_from_htmx', $_SESSION['_ci_previous_url']);
    }

    public function testDontStorePreviousURLIsHTMX(): void
    {
        $_SESSION['_ci_previous_url'] = 'https://example.com/index.php/?previous=original';

        service('uri')->setPath('/')->setQuery('previous=original');

        config('Htmx')->storePreviousURL = false;

        service('uri')->setPath('/')->setQuery('previous=not_saved_from_htmx');
        service('request')->appendHeader('HX-Request', 'true');

        ob_start();
        service('codeigniter', null, false)->setContext('web')->run();
        ob_get_clean();

        $this->assertTrue(service('request')->isHTMX());
        $this->assertSame('https://example.com/index.php/?previous=original', $_SESSION['_ci_previous_url']);
    }
}
