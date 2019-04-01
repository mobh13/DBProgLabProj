<?php
require "includes/bundle.php";
$login = new Login();

if(isset($_POST['login']))
{

$login->login($_POST["email"],$_POST["password"]);
    echo "fail";
    exit();
}
if(isset($_POST['Register']))
{

}
?>