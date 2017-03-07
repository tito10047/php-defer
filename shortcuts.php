<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:12
 */

/**
 * @param \mostka\Defer|null $e previous defer
 * @param callable           $callback
 * @param                    mixed ... $args
 *
 * @throws \Exception
 */
function defer(&$e, $callback) {
	$args = func_get_args();
	array_shift($args);
	array_shift($args);
	\mostka\Defer::deferArr($e, $callback, $args);
}
