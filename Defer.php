<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:11
 */

namespace mostka;


class Defer {

	/**
	 * @var callable
	 */
	private $callback;
	private $args;
	/**
	 * @var self[]
	 */
	private $prevDefenders = [];
	private $rootDefender  = null;
	private $isLast        = true;
	private $destructed    = false;

	private function __construct($prevDefender, $callback, $args) {
		$this->callback = $callback;
		$this->args     = $args;
		if ($prevDefender instanceof self) {
			if ($prevDefender->rootDefender == null) {
				$prevDefender->rootDefender = $prevDefender;
			}
			$prevDefender->rootDefender->prevDefenders[] = $prevDefender;
			$this->rootDefender                          = $prevDefender->rootDefender;
			foreach ($this->prevDefenders as $defender) {
				$defender->isLast = false;
			}
		}
	}

	/**
	 * @param Defer|null $e    previous defer
	 * @param            $callback
	 * @param            mixed ... $args
	 */
	public static function defer(&$e, $callback) {
		$args = func_get_args();
		array_shift($args);
		array_shift($args);
		self::deferArr($e, $callback, $args);
	}

	/**
	 * @param Defer|null $e previous defer
	 * @param callable   $callback
	 * @param array      $args
	 *
	 * @throws \Exception
	 */
	public static function deferArr(&$e, $callback, $args) {
		if (!is_callable($callback)) {
			if (is_string($callback) && !function_exists($callback)) {
				throw new \Exception("function '{$callback}' not exist");
			}
			throw new \Exception("this is not callable");
		}
		$e = new self($e, $callback, $args);
	}

	function __destruct() {
		if ($this->destructed) {
			return;
		}
		$this->destructed = true;
		call_user_func_array($this->callback, $this->args);
		if ($this->rootDefender != null) {
			for ($i = count($this->rootDefender->prevDefenders) - 1; $i >= 0; $i--) {
				/** @var self $defender */
				$defender             = $this->rootDefender->prevDefenders[$i];
				$defender->destructed = true;
				call_user_func_array($defender->callback, $defender->args);
				$this->rootDefender->prevDefenders[$i] = null;
			}
		}
	}
}