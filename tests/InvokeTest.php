<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 27. 8. 2024
 * Time: 14:38
 */
namespace Defer\Tests;

use PHPUnit\Framework\TestCase;


class InvokeTest extends TestCase
{
    public function testHelper(): void
    {
        $foo = 0;
        $this->testDefer($foo);

        $this->assertSame(3, $foo);
    }

    private function testDefer(int &$foo): void
    {
        $closure = function () use (&$foo) {
            $foo++;
        };
        $defer = defer($closure);
        $defer($closure);
        $defer($closure);
    }
}



