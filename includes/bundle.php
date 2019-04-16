<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 27/03/2019
 * Time: 10:31 AM
 */
session_start();

$debug = true;
if($debug){
    ini_set('show_errors', 'On');
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
}


function __autoload($className){
    include_once  $className.'.php';
}

// this is a support function without the need of a class
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}
?>
