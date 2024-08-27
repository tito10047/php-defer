<?php

namespace Defer;


class Defer
{

    private array $callbacks = [];

    public function __construct(?callable $callback, mixed ...$args)
    {
        $this->defer($callback, ...$args);
    }


    public function defer(?callable $callback, mixed ...$args): void
    {
        if ($callback === null) {
            return;
        }
        $this->callbacks[] = [$callback, $args];;
    }

    public function __invoke(callable $callback, mixed ...$args): void
    {
        $this->defer($callback, ...$args);
    }


    function __destruct()
    {
        $this->flush();
    }

    public function flush(): void
    {
        while ([$callback, $args] = array_pop($this->callbacks)) {
            call_user_func_array($callback, $args);
        }
    }
}