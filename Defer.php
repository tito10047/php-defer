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
	private $prevDefenders=[];
	private $rootDefender=null;
	private $isLast=true;
	private $destructed=false;

	private function __construct($callback, $args, $prevDefender) {
		$this->callback=$callback;
		if (!is_array($args)){
			if ($args===null) {
				$args = [];
			}else{
				$args=[$args];
			}
		}
		$this->args =$args;
		if ($prevDefender instanceof self){
			if ($prevDefender->rootDefender==null){
				$prevDefender->rootDefender = $prevDefender;
			}
			$prevDefender->rootDefender->prevDefenders[]=$prevDefender;
			$this->rootDefender = $prevDefender->rootDefender;
			foreach ($this->prevDefenders as $defender){
				$defender->isLast=false;
			}
		}
	}

	/**
	 * @param callable $callback
	 * @param          $args
	 * @param          $e
	 *
	 * @throws \Exception
	 */
	public static function defer($callback, $args, &$e){
		if (!is_callable($callback)){
			if (is_string($callback) && !function_exists($callback)){
				throw new \Exception("function '{$callback}' not exist");
			}
			throw new \Exception("this is not callable");
		}
		$e = new self($callback, $args, $e);
	}

	function __destruct() {
		if ($this->destructed){
			return;
		}
		$this->destructed=true;
		call_user_func_array($this->callback,$this->args);
		if ($this->rootDefender!=null) {
			foreach (array_reverse($this->rootDefender->prevDefenders) as $defender) {
				/** @var self $defender */
				$defender->destructed = true;
				call_user_func_array($defender->callback, $defender->args);
			}
		}
	}
}