<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class CommonTest extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        service('renderer', SUPPORTPATH . 'Views');
    }

    public function testViewFragmentNone(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = '';

        $this->assertSame($expected, view_fragment('view_fragment', '', $data));
    }

    public function testViewFragmentOne(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = "Hello World, fragment1!\n";

        $this->assertSame($expected, view_fragment('view_fragment', 'sample1', $data));
    }

    public function testViewFragmentTwo(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, view_fragment('view_fragment', ['sample1', 'sample2'], $data));
    }

    public function testViewFragmentSaveData(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, view_fragment('view_fragment', ['sample1', 'sample2'], $data, ['saveData' => true]));
    }

    public function testViewFragmentWithLayout(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = "Hello World, fragment1!\nHello World, fragment2!\n";

        $this->assertSame($expected, view_fragment('with_fragment', ['sample1', 'sample2'], $data));
    }

    public function testViewFragmentFromLayout(): void
    {
        $data = [
            'testString1' => 'Hello World',
            'testString2' => 'Hello World',
        ];

        $expected = 'Page bottom';

        $this->assertSame($expected, view_fragment('with_fragment', 'sample0', $data));
    }

    public function testComplexLayout(): void
    {
        $data   = ['foo' => 'FOO'];
        $result = view_fragment('complex/view1', ['fragment1'], $data + ['bar' => 'BAR'])
            . view_fragment('complex/view2', ['fragment2'], $data + ['baz' => 'BAZ'])
            . view_fragment('complex/view3', ['fragment3'], $data + ['too' => 'TOO'])
            . view_fragment('complex/include', ['include'], $data + ['inc' => 'INC']);

        $expected = <<<'EOD'
            <div>
                <b>Fragment 2</b><br>
                <b>foo: </b> YES<br>
                <b>bar: </b> YES<br>
                <b>baz: </b> YES<br>
                <b>too: </b> NO<br>
                <b>inc: </b> NO<br>
            </div>
            <div>
                <b>Fragment 3</b><br>
                <b>foo: </b> YES<br>
                <b>bar: </b> YES<br>
                <b>baz: </b> YES<br>
                <b>too: </b> YES<br>
                <b>inc: </b> NO<br>
            </div>
                <div>
                    <b>Include</b><br>
                    <b>foo: </b> YES<br>
                    <b>bar: </b> YES<br>
                    <b>baz: </b> YES<br>
                    <b>too: </b> YES<br>
                    <b>inc: </b> YES<br>
                </div>

            EOD;

        $this->assertSame($expected, $result);
    }

    public function testManySameNameFragments()
    {
        $result = view_fragment('many/view1', ['fragment1']);

        $expected = <<<'EOD'
            <b>Fragment 1 (1)</b><br>
                    <b>Fragment 1 (3)</b><br>

            EOD;

        $this->assertSame($expected, $result);
    }

    public function testHugeView(): void
    {
        $result = view_fragment('huge/view', ['fragment_one']);

        $expected = <<<'EOD'
            Fragment one (from "huge/view")
            Fragment one (from "huge/include")
            Fragment one (from "huge/layout")

            EOD;

        $this->assertSame($expected, $result);
    }
}
