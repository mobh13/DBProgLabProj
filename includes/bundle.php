<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 27/03/2019
 * Time: 10:31 AM
 */


$debug = true;
if($debug){
    ini_set('show_errors', 'On');
    ini_set('display_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
}


function __autoload($className){
    include_once  $className.'.php';
}

?>
