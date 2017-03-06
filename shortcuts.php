<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:12
 */

/**
 * @param callable $callback
 * @param          $args
 * @param          $e
 *
 * @throws \Exception
 */
function defer($callback, $args, &$e){
	\mostka\Defer::defer($callback, $args, $e);
}