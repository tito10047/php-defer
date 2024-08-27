<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 27. 8. 2024
 * Time: 14:38
 */

namespace Defer\Tests;

use PHPUnit\Framework\TestCase;


class ShortcutTest extends TestCase
{
    public function testHelper(): void
    {
        $foo = 0;
        $this->testDefer($foo);

        $this->assertSame(1, $foo);
    }

    private function testDefer(int &$foo): void
    {
        $_ = defer(function () use (&$foo) {
            $foo++;
        });
        $this->assertSame(0, $foo);
    }
}



