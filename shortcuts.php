<?php

namespace {

    use Defer\Defer;


    function defer(?callable $callback = null, mixed ...$args): Defer
    {
        return new Defer($callback, ...$args);
    }
}