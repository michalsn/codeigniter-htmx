<?php

declare(strict_types=1);

namespace Tests\HTTP;

use CodeIgniter\HTTP\URI;
use CodeIgniter\HTTP\UserAgent;
use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use InvalidArgumentException;
use Michalsn\CodeIgniterHtmx\HTTP\IncomingRequest;

/**
 * @internal
 */
final class IncomingRequestTest extends CIUnitTestCase
{
    private IncomingRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new IncomingRequest(new App(), new URI(), null, new UserAgent());
    }

    public function testIsHtmx(): void
    {
        $this->request->appendHeader('HX-Request', 'true');
        $this->assertTrue($this->request->isHtmx());
    }

    public function testIsHtmxIsFalse(): void
    {
        $this->assertFalse($this->request->isHtmx());

        $this->request->appendHeader('HX-Request', 'foo');
        $this->assertFalse($this->request->isHtmx());
    }

    public function testIsBoosted(): void
    {
        $this->request->appendHeader('HX-Boosted', 'true');
        $this->assertTrue($this->request->isBoosted());
    }

    public function testIsBoostedIsFalse(): void
    {
        $this->assertFalse($this->request->isBoosted());

        $this->request->appendHeader('HX-Boosted', 'foo');
        $this->assertFalse($this->request->isBoosted());
    }

    public function testIsHistoryRestoreRequest(): void
    {
        $this->request->appendHeader('HX-History-Restore-Request', 'true');
        $this->assertTrue($this->request->isHistoryRestoreRequest());
    }

    public function testIsHistoryRestoreRequestIsFalse(): void
    {
        $this->assertFalse($this->request->isHistoryRestoreRequest());

        $this->request->appendHeader('HX-History-Restore-Request', 'foo');
        $this->assertFalse($this->request->isHistoryRestoreRequest());
    }

    public function testGetRequestType(): void
    {
        $header = 'partial';
        $this->request->appendHeader('HX-Request-Type', $header);
        $this->assertSame($header, $this->request->getRequestType());
    }

    public function testGetRequestTypeIsNull(): void
    {
        $this->assertNull($this->request->getRequestType());
    }

    public function testIsPartial(): void
    {
        $this->request->appendHeader('HX-Request-Type', 'partial');
        $this->assertTrue($this->request->isPartial());
        $this->assertFalse($this->request->isFull());
    }

    public function testIsFull(): void
    {
        $this->request->appendHeader('HX-Request-Type', 'full');
        $this->assertTrue($this->request->isFull());
        $this->assertFalse($this->request->isPartial());
    }

    public function testGetCurrentUrl(): void
    {
        $header = 'https://codeigniter-htmx-demo.test/';
        $this->request->appendHeader('HX-Current-Url', $header);
        $this->assertSame($header, $this->request->getCurrentUrl());
    }

    public function testGetCurrentUrlIsNull(): void
    {
        $this->assertNull($this->request->getCurrentUrl());
    }

    public function testGetTarget(): void
    {
        $header = 'div#response-div';
        $this->request->appendHeader('HX-Target', $header);
        $this->assertSame($header, $this->request->getTarget());
    }

    public function testGetTargetIsNull(): void
    {
        $this->assertNull($this->request->getTarget());
    }

    public function testGetSource(): void
    {
        $header = 'button#test-id';
        $this->request->appendHeader('HX-Source', $header);
        $this->assertSame($header, $this->request->getSource());
    }

    public function testGetSourceIsNull(): void
    {
        $this->assertNull($this->request->getSource());
    }

    public function testIsMethodWithHtmxParam(): void
    {
        $request = $this->request->setHeader('HX-Request', 'true');
        $this->assertTrue($request->is('htmx'));
    }

    public function testIsMethodWithBoostedParam(): void
    {
        $request = $this->request->setHeader('HX-Boosted', 'true');
        $this->assertTrue($request->is('boosted'));
    }

    public function testIsMethodWithPartialParam(): void
    {
        $request = $this->request->setHeader('HX-Request-Type', 'partial');
        $this->assertTrue($request->is('partial'));
    }

    public function testIsMethodWithFullParam(): void
    {
        $request = $this->request->setHeader('HX-Request-Type', 'full');
        $this->assertTrue($request->is('full'));
    }

    public function testIsMethodWithInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown type: invalid');

        $this->assertTrue($this->request->is('invalid'));
    }
}
