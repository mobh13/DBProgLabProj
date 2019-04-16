<?php

require_once "includes/bundle.php";

$login = new Login();

if($login->check_session()){

    $login->logout();
    header("location:index.php");
}else{

    die("You are not signed in !!");
}
?>
