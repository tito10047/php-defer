<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 27. 8. 2024
 * Time: 14:38
 */

namespace Defer\Tests;

use PHPUnit\Framework\TestCase;


class BlankConstructorTest extends TestCase
{
    public function testBasics(): void
    {
        $stack = [];
        $this->calDefer(function ($item) use (&$stack) {
            $stack[] = $item;
        });
        $stack[] = "end";
        for ($i = 5; $i >= 0; $i--) {
            $this->assertEquals($i, array_shift($stack));
        }
    }

    private function calDefer(callable $callback): void
    {
        $defer = defer();
        for ($i = 0; $i < 5; $i++) {
            $defer->defer($callback, $i);
        }
        $defer->defer($callback, $i);
    }

}