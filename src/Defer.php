<?php
namespace Defer;


class Defer
{

    private array $callbacks = [];

    public function __construct(callable|string $callback, mixed ...$args)
    {
        $this->defer($callback, ...$args);
    }


    public function defer(callable|string $callback, mixed ...$args): void
    {
        if (is_string($callback)) {
            if (!function_exists($callback)) {
                throw new \InvalidArgumentException("Function $callback does not exist");
            }
        }
        $this->callbacks[] = [$callback, $args];;
    }

    function __destruct()
    {
        foreach (array_reverse($this->callbacks) as [$callback, $args]) {
            call_user_func_array($callback, $args);
        }
    }
}