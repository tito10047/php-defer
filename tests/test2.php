<?php
namespace test;
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:37
 */


require_once __DIR__.'/../Defer.php';
require_once __DIR__.'/../shortcuts.php';

function a(){
	$i=0;
	defer('printf',$i,$e);
	$i++;
}

function b(){
	for($i=0;$i<4;$i++){
		defer('printf',$i,$a);
	}
}

function c() {
	$i=1;
	defer(function () use (&$i) {
		$i++;
		echo $i.PHP_EOL;
	},null, $e);

	return $i;
}

echo c().PHP_EOL;