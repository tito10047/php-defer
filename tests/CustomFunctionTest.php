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

global $fpp;
$fpp = 0;
function foo(int $a):void{
    global $fpp;
    $fpp+=$a;
}

class CustomFunctionTest extends TestCase
{
    public function testBasics():void
    {
        $this->incrementFpp();
        global $fpp;
        $this->assertEquals(1, $fpp);
    }

    private function incrementFpp():void
    {
        $defer = new Defer('Defer\Tests\foo', 1);
    }
}