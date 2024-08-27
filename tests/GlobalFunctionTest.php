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


class GlobalFunctionTest extends TestCase
{
    public function testBasics():void
    {
        $file = fopen(__DIR__.'/test.txt', 'w+');
        $this->calDefer($file);
        $this->assertIsClosedResource($file);
        unlink(__DIR__.'/test.txt');
    }

    private function calDefer($param):void
    {
        $defer = new Defer('fclose',$param);
    }
}