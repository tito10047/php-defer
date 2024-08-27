<?php
namespace {

    use Defer\Defer;


    function defer(callable $callback, mixed ...$args): Defer
    {
        return new Defer($callback, ...$args);
    }
}