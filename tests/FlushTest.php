<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 27. 8. 2024
 * Time: 14:38
 */

namespace Defer\Tests;

use Defer\Defer;
use PHPUnit\Framework\TestCase;


class FlushTest extends TestCase
{
    public function testBasics(): void
    {
        $foo = 0;
        $this->testDefer($foo);

        $this->assertSame(1, $foo);
    }

    private function testDefer(int &$foo): void
    {
        $defer = new Defer(function () use (&$foo) {
            $foo++;
        });
        $defer->flush();
        $this->assertSame(1, $foo);
        $defer->flush();
        $this->assertSame(1, $foo);
    }


}