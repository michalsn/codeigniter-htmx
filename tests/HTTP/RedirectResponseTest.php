<?php

namespace Tests\HTTP;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use InvalidArgumentException;
use Michalsn\CodeIgniterHtmx\HTTP\RedirectResponse;

/**
 * @internal
 */
final class RedirectResponseTest extends CIUnitTestCase
{
    private RedirectResponse $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new RedirectResponse(new App());
    }

    public function testHxLocation(): void
    {
        $this->response = $this->response->hxLocation('foo');

        $this->assertTrue($this->response->hasHeader('HX-Location'));
        $expected = json_encode(['path' => '/foo']);
        $this->assertSame($expected, $this->response->getHeaderLine('HX-Location'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxLocationWithFullPath(): void
    {
        $this->response = $this->response->hxLocation('https://example.com/foo1');

        $this->assertSame(json_encode(['path' => '/foo1']), $this->response->getHeaderLine('HX-Location'));

        $this->response = $this->response->hxLocation('http://example.com/foo2');

        $this->assertSame(json_encode(['path' => '/foo2']), $this->response->getHeaderLine('HX-Location'));

        $this->response = $this->response->hxLocation('http://example.com/foo3?page=1&sort=ASC#top');

        $this->assertSame(json_encode(['path' => '/foo3?page=1&sort=ASC#top']), $this->response->getHeaderLine('HX-Location'));
    }

    public function testHxLocationWithSourceAndEvent(): void
    {
        $this->response = $this->response->hxLocation(path: '/foo', source: '#myElem', event: 'doubleclick');

        $this->assertTrue($this->response->hasHeader('HX-Location'));
        $expected = json_encode(['path' => '/foo', 'source' => '#myElem', 'event' => 'doubleclick']);
        $this->assertSame($expected, $this->response->getHeaderLine('HX-Location'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxLocationWithTargetAndSwap(): void
    {
        $this->response = $this->response->hxLocation(path: '/foo', target: '#myDiv', swap: 'outerHTML');

        $this->assertTrue($this->response->hasHeader('HX-Location'));
        $expected = json_encode(['path' => '/foo', 'target' => '#myDiv', 'swap' => 'outerHTML']);
        $this->assertSame($expected, $this->response->getHeaderLine('HX-Location'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxLocationWithValuesAndHeaders(): void
    {
        $this->response = $this->response->hxLocation(path: '/foo', values: ['myVal' => 'My Value'], headers: ['myHeader' => 'My Value']);

        $this->assertTrue($this->response->hasHeader('HX-Location'));
        $expected = json_encode(['path' => '/foo', 'values' => ['myVal' => 'My Value'], 'headers' => ['myHeader' => 'My Value']]);
        $this->assertSame($expected, $this->response->getHeaderLine('HX-Location'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxLocationThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->response = $this->response->hxLocation(path: '/foo', swap: 'foo');
    }

    public function testHxRedirect(): void
    {
        $this->response = $this->response->hxRedirect('foo');

        $this->assertTrue($this->response->hasHeader('HX-Redirect'));
        $this->assertSame('https://example.com/index.php/foo', $this->response->getHeaderLine('HX-Redirect'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxRedirectWithFullUrl(): void
    {
        $this->response = $this->response->hxRedirect('http://example.com/foo');

        $this->assertTrue($this->response->hasHeader('HX-Redirect'));
        $this->assertSame('http://example.com/foo', $this->response->getHeaderLine('HX-Redirect'));
        $this->assertSame(200, $this->response->getStatusCode());
    }

    public function testHxRefresh(): void
    {
        $this->response = $this->response->hxRefresh();

        $this->assertTrue($this->response->hasHeader('HX-Refresh'));
        $this->assertSame('true', $this->response->getHeaderLine('HX-Refresh'));
        $this->assertSame(200, $this->response->getStatusCode());
    }
}
