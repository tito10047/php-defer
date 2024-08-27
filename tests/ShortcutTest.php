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
        defer(function () use (&$foo) {
            $foo++;
        });
    }
}



