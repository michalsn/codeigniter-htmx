<?php

declare(strict_types=1);

namespace Tests\HTTP;

use CodeIgniter\Exceptions\InvalidArgumentException;
use CodeIgniter\Test\CIUnitTestCase;
use Config\App;
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
        $this->response->setPushUrl();

        $this->assertSame('false', $this->response->getHeaderLine('HX-Push-Url'));
    }

    public function testSetReplaceUrl(): void
    {
        $this->response->setReplaceUrl('/foo');

        $this->assertSame('/foo', $this->response->getHeaderLine('HX-Replace-Url'));
    }

    public function testSetReplaceUrlFalse(): void
    {
        $this->response->setReplaceUrl();

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

    public function testSetReswapWithTextContent(): void
    {
        $this->response->setReswap('textContent');

        $this->assertSame('textContent', $this->response->getHeaderLine('HX-Reswap'));
    }

    public function testSetReswapWithAlias(): void
    {
        $this->response->setReswap('append');

        $this->assertSame('append', $this->response->getHeaderLine('HX-Reswap'));
    }

    public function testSetReswapWithMorph(): void
    {
        $this->response->setReswap('innerMorph');

        $this->assertSame('innerMorph', $this->response->getHeaderLine('HX-Reswap'));
    }

    public function testSetReswapWithOuterSync(): void
    {
        $this->response->setReswap('outerSync');

        $this->assertSame('outerSync', $this->response->getHeaderLine('HX-Reswap'));
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
            $this->response->getHeaderLine('HX-Trigger'),
        );
    }

    public function testTriggerClientEventAndPassDetails(): void
    {
        $this->response->triggerClientEvent('showMessage', ['level' => 'info', 'message' => 'Here Is A Message']);

        $this->assertSame(
            '{"showMessage":{"level":"info","message":"Here Is A Message"}}',
            $this->response->getHeaderLine('HX-Trigger'),
        );
    }

    public function testTriggerClientEventAndPassDetailsMultipleCalls(): void
    {
        $this->response->triggerClientEvent('event1', 'A message');
        $this->response->triggerClientEvent('event2', 'Another message');

        $this->assertSame(
            '{"event1":"A message","event2":"Another message"}',
            $this->response->getHeaderLine('HX-Trigger'),
        );
    }

    public function testTriggerClientEventThrowInvalidArgumentExceptionForHeaderContent(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->response->setHeader('HX-Trigger', 'foo');

        $this->response->triggerClientEvent('event1', 'A message');
    }
}
