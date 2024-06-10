<?php

namespace Tests\Format;

use CodeIgniter\Test\CIUnitTestCase;
use Michalsn\CodeIgniterHtmx\Format\HTMLFormatter;

/**
 * @internal
 */
final class HTMLFormatterTest extends CIUnitTestCase
{
    private HTMLFormatter $htmlFormatter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->htmlFormatter = new HTMLFormatter();
    }

    public function testEmptyHTML(): void
    {
        $data = [];

        $expected = '';

        $this->assertSame($expected, $this->htmlFormatter->format($data));
    }

    public function testBasicHTML(): void
    {
        $data = '<div>Hello</div>';

        $expected = '<div>Hello</div>';

        $this->assertSame($expected, $this->htmlFormatter->format($data));
    }

    public function testFormatFailArray(): void
    {
        $data = [
            'status'   => 400,
            'error'    => 400,
            'messages' => ['error' => '<div>Error</div>'],
        ];

        $expected = '<div>Error</div>';

        $this->assertSame($expected, $this->htmlFormatter->format($data));
    }

    public function testFormatFailArrayWithManyMessages(): void
    {
        $data = [
            'status'   => 400,
            'error'    => 400,
            'messages' => ['error' => '<div>Error</div>', 'example' => '<div>Example</div>'],
        ];

        $expected = <<<'EOH'
            <table border="0" cellpadding="4" cellspacing="0">
            <thead>
            <tr>
            <th>error</th><th>example</th></tr>
            </thead>
            <tbody>
            <tr>
            <td><div>Error</div></td><td><div>Example</div></td></tr>
            </tbody>
            </table>
            EOH;

        $this->assertSame($expected, $this->htmlFormatter->format($data));
    }
}
