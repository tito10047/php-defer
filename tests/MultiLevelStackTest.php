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


class MultiLevelStackTest extends TestCase
{
    public function testBasics(): void
    {
        $stack = [];
        $this->callLevel(1, function ($item) use (&$stack) {
            $stack[] = $item;
        });
        for ($level = 3; $level >= 1; $level--) {
            for ($i = 3; $i >= 0; $i--) {
                $this->assertEquals("{$level}:{$i}", array_shift($stack));
            }
        }
    }

    private function callLevel(int $level, callable $callback): void
    {
        $defer = new Defer($callback, "{$level}:0");
        for ($i = 1; $i < 3; $i++) {
            $defer->defer($callback, "{$level}:{$i}");
        }
        if ($level < 3) {
            $this->callLevel($level + 1, $callback);
        }
        $defer->defer($callback, "{$level}:{$i}");
    }

}