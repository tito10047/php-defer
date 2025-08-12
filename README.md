![Tests](https://github.com/tito10047/php-defer/actions/workflows/unit-test.yml/badge.svg)
[![Coverage Status](https://coveralls.io/repos/github/tito10047/php-defer/badge.svg?branch=master)](https://coveralls.io/github/tito10047/php-defer?branch=master)

# php-defer

### A php implementation of [defer](https://golang.org/doc/effective_go.html#defer) statement from [Go](https://golang.org/)

PHP defer function schedules a function call (the deferred function) to be run immediately before the function
executing the defer returns. It's an unusual but effective way to deal with situations such as resources that must be
released regardless of which path a function takes to return. The canonical examples are unlocking a mutex or closing a
file.

```php
// Contents returns the file's contents as a string.
function contents($filename) {
    $f = fopen($filename, "r");
    if ($f === false) {
        throw new Exception("Error opening the file");
    }
    $defer = defer(fclose(...),$f);  // fclose will run when we're finished.

    $result = ""; 

    while (($buffer = fread($f, 100)) !== false) {
        $result .= $buffer; 
    }

    if (feof($f) === false) {
        // $f will be closed if we return here.
        throw new Exception("Error reading the file");
    }

    // $f will be closed if we return here.
    return $result;
}
```

Deferring a call to a function such as Close has two advantages. First, it guarantees that you will never forget to
close the file, a mistake that's easy to make if you later edit the function to add a new return path. Second, it means
that the close sits near the open, which is much clearer than placing it at the end of the function.

---

### Installation

```shell
composer require tito10047/php-defer 
```

## Quick example

```php
function foo($a){
    echo "in defer {$a}".PHP_EOL;
}
function a() {
    echo "before defer".PHP_EOL;
    $defer = defer(foo(...),1);
    $defer(foo(...),2);
    $defer(foo(...),3);
    echo "after defer".PHP_EOL;
};

echo "start".PHP_EOL;
a();
echo "end".PHP_EOL;
```

will print

```text
start
before defer
after defer
in defer 3
in defer 2
in defer 1
end
```

### 3 Rules

--------
The behavior of defer statements is straightforward and predictable. There are three simple rules:

`1.` *A deferred function's arguments are evaluated when the defer statement is evaluated.*

In this example, the expression "i" is evaluated when the printf call is deferred.
The deferred call will print `0` after the function returns.

```php
function a(){
    $i=0;
    $_ = defer(printf(...),$i);
    $i++;
}
```

will print ```000```

`2.` *Deferred function calls are executed in Last In First Out order after the*
surrounding function returns.

This function prints `3210`:

```php
function b(){
    $defer = defer();
    for($i=0;$i<4;$i++){
        $defer(printf(...),$i);
    }
}
```

`3.` *Deferred functions can`t modify return values when is type, but can modify content of
reference to array or object.*

In this example, a deferred function increments increment `$o->i` *after* the surrounding
function returns but not modify returned `$i`. This example print `2-3`:

```php
function c() {
    $i=1;
    $o=new \stdClass();
    $o->i=2;
    $defer = defer(function () use (&$i, $o) {
        $o->i++;
        $i++;
    });

    $i++;
    return [$i,$o];
}
list($i,$o) = c();
echo "{$i}-{$o->i}".PHP_EOL;
```

---

### PHP Limitations

- In php defer implementation you can't modify returned value. Can modify only content of returned reference
- You need instantiate defer object before use it with ```$defer = new Defer()``` or ```$defer = defer()```

---

### Usage

```php
namespace test;

require_once __DIR__.'/../vendor/autoload.php';

function myFunc(){}
class Foo{
    public function myMethod(){}
}
function a(){
    // defer custom function without parameter
    // function name must be with his namespace
    $defer = defer('test\myFunc');
    // defer function with one parameter
    $defer(printf(...),"test");
    // defer function with more parameters
    $defer('printf',"%s-%s",10,12);
    // defer with anonymous function
    $defer(function (){});
    $func = function (){};
    $defer($func);
    //defer method
    $foo = new Foo();
    $defer([$foo,'myMethod']);
}
a();
```
