<?php
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 6.3.2017
 * Time: 10:15
 */

require_once __DIR__ . '/../Defer.php';
require_once __DIR__ . '/../shortcuts.php';

/**
 * @param $dstName
 * @param $srcName
 *
 * @return bool
 */
function copyFileBad($srcName, $dstName)
{
    $src = fopen($srcName, 'r');
    if ($src === false) {
        return false;
    }
    $dst = fopen($dstName, 'w');
    if ($dst === false) {
        return false;
    }
    $size = filesize($srcName);
    while ($size > 0) {
        $s = $size > 1000 ? 1000 : $size;
        fwrite($dst, fread($src, $s));
        $size -= 1000;
    }

    fclose($src);
    fclose($dst);
    return true;
}

function copyFile($srcName, $dstName)
{
    $src = fopen($srcName, 'r');
    if ($src === false) {
        return false;
    }
    $defer = defer(fclose(...), $src);

    $dst = fopen($dstName, 'w');
    if ($dst === false) {
        return false;
    }
    $defer(fclose(...), $dst);

    $size = filesize($srcName);
    while ($size > 0) {
        $s = $size > 1000 ? 1000 : $size;
        $b = fwrite($dst, fread($src, $s));
        if ($s != $b) {
            return false;
        }
        $size -= 1000;
    }

    return true;
}
