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


class AnonymousFunctionTest extends TestCase
{
    public function testBasics():void
    {
        $foo = 1;
        $this->calDefer(function ($param) use(&$foo){
            $foo = $param;
        }, 2);
        $this->assertEquals(2, $foo);
    }

    private function calDefer(callable $method, $param):void
    {
        $defer = new Defer($method,$param);
    }
}