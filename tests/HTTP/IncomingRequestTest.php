<?php

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

    public function testGetPrompt(): void
    {
        $header = 'prompt test';
        $this->request->appendHeader('HX-Prompt', $header);
        $this->assertSame($header, $this->request->getPrompt());
    }

    public function testGetPromptIsNull(): void
    {
        $this->assertNull($this->request->getPrompt());
    }

    public function testGetTarget(): void
    {
        $header = '#response-div';
        $this->request->appendHeader('HX-Target', $header);
        $this->assertSame($header, $this->request->getTarget());
    }

    public function testGetTargetIsNull(): void
    {
        $this->assertNull($this->request->getTarget());
    }

    public function testGetTrigger(): void
    {
        $header = 'test-id';
        $this->request->appendHeader('HX-Trigger', $header);
        $this->assertSame($header, $this->request->getTrigger());
    }

    public function testGetTriggerIsNull(): void
    {
        $this->assertNull($this->request->getTrigger());
    }

    public function testGetTriggerName(): void
    {
        $header = 'test-name';
        $this->request->appendHeader('HX-Trigger-Name', $header);
        $this->assertSame($header, $this->request->getTriggerName());
    }

    public function testGetTriggerNameIsNull(): void
    {
        $this->assertNull($this->request->getTriggerName());
    }

    public function testGetTriggeringEvent(): void
    {
        $header = '{"isTrusted":true,"htmx-internal-data":{"triggerSpec":{"trigger":"click"},"handledFor":["button.btn.btn-sm btn-primary"]},"screenX":1347,"screenY":238,"pageX":106,"pageY":128,"clientX":106,"clientY":128,"x":106,"y":128,"offsetX":93,"offsetY":11,"ctrlKey":false,"shiftKey":false,"altKey":false,"metaKey":false,"button":0,"buttons":0,"relatedTarget":null,"movementX":0,"movementY":0,"mozPressure":0,"mozInputSource":1,"MOZ_SOURCE_UNKNOWN":0,"MOZ_SOURCE_MOUSE":1,"MOZ_SOURCE_PEN":2,"MOZ_SOURCE_ERASER":3,"MOZ_SOURCE…ck","target":"button.btn.btn-sm btn-primary","srcElement":"button.btn.btn-sm btn-primary","currentTarget":"button.btn.btn-sm btn-primary","eventPhase":2,"bubbles":true,"cancelable":true,"returnValue":true,"defaultPrevented":false,"composed":true,"timeStamp":1599,"cancelBubble":false,"originalTarget":"button.btn.btn-sm btn-primary","explicitOriginalTarget":"button.btn.btn-sm btn-primary","NONE":0,"CAPTURING_PHASE":1,"AT_TARGET":2,"BUBBLING_PHASE":3,"ALT_MASK":1,"CONTROL_MASK":2,"SHIFT_MASK":4,"META_MASK":8}';
        $this->request->appendHeader('Triggering-Event', $header);
        $this->assertSame(json_decode($header), $this->request->getTriggeringEvent());
    }

    public function testGetTriggeringEventIsNull(): void
    {
        $this->assertNull($this->request->getTriggeringEvent());
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

    public function testIsMethodWithInvalidParam(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown type: invalid');

        $this->assertTrue($this->request->is('invalid'));
    }
}
