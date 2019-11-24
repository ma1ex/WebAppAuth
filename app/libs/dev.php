<?php

/**
 * Project: auth.local;
 * File: dev.php;
 * Developer: Matvienko Alexey (matvienko.alexey@gmail.com);
 * Date & Time: 22.11.2019, 18:31
 * Comment:
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

/**
 * @param $var
 * uses var_dump()
 */
function debug_v($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    exit();
}

/**
 * @param $var
 * uses print_r()
 */
function debug_p($var) {
    echo '<pre>';
    print_r($var);
    echo '</pre>';
    exit();
}

/**
 * @param $size int Size
 * @return string Rounded size with unit
 */
function convert($size) {
    $unit = ['b','kb','mb','gb','tb','pb'];
    return @round($size / pow(1024, ($i = floor(log($size,1024)))),2) . ' ' . $unit[$i];
}

/**
 * @param string $type : "total" or empty for current memory;
 *                       "peak" - peak memory.
 *
 * Get formatted memory usage info.
 */
function getMemory($type = 'total') {
    if ($type === 'total') {
        $info = 'Total memory: ' . convert(memory_get_usage());
        echo '<p style="padding: 5px; border: solid 1px orange; background: lightyellow;">' . $info . '</p>';
    }

    if ($type === 'peak') {
        $info = 'Peak memory: ' . convert(memory_get_peak_usage());
        echo '<p style="padding: 5px; border: solid 1px orange; background: lightyellow;">' . $info . '</p>';
    }

}