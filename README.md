# php-defer
### A php implementation of [defer](https://blog.golang.org/defer-panic-and-recover) statement from [Go](https://golang.org/)
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
		fwrite($dst,fread($src,$s));
		$size-=1000;
	}

	fclose($src);
	fclose($dst);
	return true;
}
```

This works, but there is a bug. If the call to fopen fails, the function 
will return without closing the source file. This can be easily remedied by 
putting a call to fclose before the second return statement, but if the 
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
		fwrite($dst,fread($src,$s));
		$size-=1000;
	}

	return true;
}
```

Defer statements allow us to think about closing each file right after opening it, guaranteeing that, regardless of the number of return statements in the function, the files will be closed.

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

`3.` *Deferred functions can`t modify return values when is type, but can content of reference to array or object.*

In this example, a deferred function increments increment `$o->i` *after* the surrounding
function returns but not modify returned `$i`. Thus, this example print `2-3`:
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

