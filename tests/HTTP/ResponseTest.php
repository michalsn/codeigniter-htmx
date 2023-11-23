<?php

namespace Tests\HTTP;

use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
use InvalidArgumentException;
use Michalsn\CodeIgniterHtmx\HTTP\Response;

/**
 * @internal
 */
final class ResponseTest extends CIUnitTestCase
{
    private Response $response;

    protected function setUp(): void
    {
        parent::setUp();

        $this->response = new Response(new App());
    }

    public function testSetPushUrl(): void
    {
        $this->response->setPushUrl('/foo');

        $this->assertSame('/foo', $this->response->getHeaderLine('HX-Push-Url'));
    }

    public function testSetPushUrlFalse(): void
    {
        $this->response->setPushUrl(null);

        $this->assertSame('false', $this->response->getHeaderLine('HX-Push-Url'));
    }

    public function testSetReplaceUrl(): void
    {
        $this->response->setReplaceUrl('/foo');

        $this->assertSame('/foo', $this->response->getHeaderLine('HX-Replace-Url'));
    }

    public function testSetReplaceUrlFalse(): void
    {
        $this->response->setReplaceUrl(null);

        $this->assertSame('false', $this->response->getHeaderLine('HX-Replace-Url'));
    }

    public function testSetReswap(): void
    {
        $this->response->setReswap('afterbegin');

        $this->assertSame('afterbegin', $this->response->getHeaderLine('HX-Reswap'));
    }

    public function testSetReswapWithModifier(): void
    {
        $this->response->setReswap('innerHTML swap:1s');

        $this->assertSame('innerHTML swap:1s', $this->response->getHeaderLine('HX-Reswap'));
    }

    public function testSetReswapThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->response->setReswap('foo');
    }

    public function testSetRetarget(): void
    {
        $this->response->setRetarget('#element');

        $this->assertSame('#element', $this->response->getHeaderLine('HX-Retarget'));
    }

    public function testSetReselect(): void
    {
        $this->response->setReselect('#element');

        $this->assertSame('#element', $this->response->getHeaderLine('HX-Reselect'));
    }

    public function testTriggerClientEvent(): void
    {
        $this->response->triggerClientEvent('showMessage');

        $this->assertSame(
            '{"showMessage":""}',
            $this->response->getHeaderLine('HX-Trigger')
        );
    }

    public function testTriggerClientEventAndPassDetails(): void
    {
        $this->response->triggerClientEvent('showMessage', ['level' => 'info', 'message' => 'Here Is A Message']);

        $this->assertSame(
            '{"showMessage":{"level":"info","message":"Here Is A Message"}}',
            $this->response->getHeaderLine('HX-Trigger')
        );
    }

    public function testTriggerClientEventAndPassDetailsMultipleCalls(): void
    {
        $this->response->triggerClientEvent('event1', 'A message');
        $this->response->triggerClientEvent('event2', 'Another message');

        $this->assertSame(
            '{"event1":"A message","event2":"Another message"}',
            $this->response->getHeaderLine('HX-Trigger')
        );
    }

    public function testTriggerClientEventWithSettle(): void
    {
        $this->response->triggerClientEvent('showMessage', '', 'settle');

        $this->assertSame(
            '{"showMessage":""}',
            $this->response->getHeaderLine('HX-Trigger-After-Settle')
        );
    }

    public function testTriggerClientEventWithSwap(): void
    {
        $this->response->triggerClientEvent('showMessage', '', 'swap');

        $this->assertSame(
            '{"showMessage":""}',
            $this->response->getHeaderLine('HX-Trigger-After-Swap')
        );
    }

    public function testTriggerClientEventThrowInvalidArgumentException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->response->triggerClientEvent('event1', 'A message', 'foo');
    }

    public function testTriggerClientEventThrowInvalidArgumentExceptionForHeaderContent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->response->setHeader('HX-Trigger', 'foo');

        $this->response->triggerClientEvent('event1', 'A message');
    }
}
