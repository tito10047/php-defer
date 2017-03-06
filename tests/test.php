<?php
namespace test;
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:35
 */

require_once __DIR__.'/../Defer.php';
require_once __DIR__.'/../shortcuts.php';

echo "start".PHP_EOL;
function eee($a){
	echo "in defer 3-{$a}".PHP_EOL;
}
function test() {
	echo "before defer".PHP_EOL;
	defer(function ($a) {
		echo "in defer 1-{$a}".PHP_EOL;
	},[1], $e);
	defer(function ($a) {
		echo "in defer 2-{$a}".PHP_EOL;
	},[2], $e);
	defer("test\\eee",[3], $e);
	echo "after defer".PHP_EOL;
};
test();
echo "end".PHP_EOL;