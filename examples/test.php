<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:35
 */

require_once __DIR__.'/../Defer.php';
require_once __DIR__.'/../shortcuts.php';

echo "start".PHP_EOL;
function foo($a){
	echo "in defer 3-{$a}".PHP_EOL;
}
function a() {
	echo "before defer".PHP_EOL;
	$defer = defer( "foo",1);
    $defer( "foo",2);
    $defer("foo",3);
	echo "after defer".PHP_EOL;
};
a();
echo "end".PHP_EOL;