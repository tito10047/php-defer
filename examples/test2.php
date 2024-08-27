<?php

namespace test;

/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:37
 */


require_once __DIR__ . '/../Defer.php';
require_once __DIR__ . '/../shortcuts.php';

function a()
{
    $i = 0;
    defer(printf(...), $i);
    $i++;
}

a();
echo PHP_EOL;

function b()
{
    $defer = defer();
    for ($i = 0; $i < 4; $i++) {
        $defer(printf(...), $i);
    }
}

b();
echo PHP_EOL;

function c()
{
    $i = 1;
    $o = new \stdClass();
    $o->i = 2;
    defer(function () use (&$i, $o) {
        $o->i++;
        $i++;
    });

    $i++;
    return [$i, $o];
}

list($i, $o) = c();
echo "{$i}-{$o->i}" . PHP_EOL;