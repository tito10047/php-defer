# php-defer
### A php implementation of [defer](https://golang.org/doc/effective_go.html#defer) statement from [Go](https://golang.org/)
--------

A defer statement pushes a function call onto a list. The list of saved calls 
is executed after the surrounding function returns. Defer is commonly used to 
simplify functions that perform various clean-up actions.

For example, let's look at a function that opens two files and copies the 
contents of one file to the other:
```php
function copyFile($srcName, $dstName){
	$src = fopen($srcName, 'r');
	if ($src===false){
		return false;
	}
	$dst = fopen($dstName, 'w');
	if ($dst===false){
		return false;
	}
	$size=filesize($srcName);
	while($size>0){
		$s = $size>1000?1000:$size;
		$b=fwrite($dst,fread($src,$s));
		if ($s!=$b){
			return false;
		}
		$size-=1000;
	}

	fclose($src);
	fclose($dst);
	return true;
}
```

This works, but there is a bug. If the call to open dst file fails or writing failed, the function
will return without closing the source file. This can be easily remedied by 
putting a call to fclose before the second and third return statement, but if the
function were more complex the problem might not be so easily noticed and resolved. 
By defer statements we can ensure that the files are always closed:
```php
function copyFile($srcName, $dstName){
	$src = fopen($srcName, 'r');
	if ($src===false){
		return false;
	}
	defer('fclose',$src,$a);

	$dst = fopen($dstName, 'w');
	if ($dst===false){
		return false;
	}
	defer('fclose',$dst,$a);

	$size=filesize($srcName);
	while($size>0){
		$s = $size>1000?1000:$size;
		$b=fwrite($dst,fread($src,$s));
		if ($s!=$b){
			return false;
		}
		$size-=1000;
	}

	return true;
}
```

Defer statements allow us to think about closing each file right after opening it, guaranteeing
that, regardless of the number of return statements in the function, the files will be closed.

--------
The behavior of defer statements is straightforward and predictable. There are three simple rules:

`1.` *A deferred function's arguments are evaluated when the defer statement is evaluated.*

In this example, the expression "i" is evaluated when the printf call is deferred.
The deferred call will print `0` after the function returns.

```php
function a(){
	$i=0;
	defer('printf',$i,$e);
	$i++;
}
```

`2.` *Deferred function calls are executed in Last In First Out order after the*
surrounding function returns.

This function prints `3210`:
```php
function b(){
	for($i=0;$i<4;$i++){
		defer('printf',$i,$a);
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
	defer(function () use (&$i, $o) {
		$o->i++;
		$i++;
	},null, $e);

	$i++;
	return [$i,$o];
}
list($i,$o) = c();
echo "{$i}-{$o->i}".PHP_EOL;
```
---
### PHP Limitations

- In php defer implementation you cant modify returned value. Can modify only content of returned reference
- You must always set third parameter in defer function, and must have same name in one closure
- you can`t pass function declared in scope by name to defer

---
### Installation

```shell
composer require mostka/defer 
```

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
	// this is not needed but some IDE show errors if is not here
	/** @var null $a or can me here only $a=null*/
	// defer custom function without parameter
	// function name must be with his namespace
	defer('test\myFunc',null,$a);
	// defer function with null parameter
	defer('var_dump',[null],$a);
	// defer function with one parameter
	defer('printf',"test",$a);
	// defer function with more parameters
	defer('printf',["%s-%s",10,12],$a);
	// defer with anonymous function
	defer(function (){},null,$a);
	$func = function (){};
	defer($func,null,$a);
	//defer method
	$foo = new Foo();
	defer([$foo,'myMethod'],null,$a);
	//this is not working, function has access only in this closure
	//function foo(){}
	//defer('foo',null,$a);
}
a();
```