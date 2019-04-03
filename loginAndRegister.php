<?php
require "includes/bundle.php";
$login = new Login();

if(isset($_POST['login']))
{
$email = $_POST["email"];
$password = $_POST["password"];
    if (!$login->login($email,$password)){
        echo "fail";
        exit();
    }else{
        echo "suc";
        exit();
    }

}
if(isset($_POST['Register']))
{

    if($login->initWithEmail($_POST['email'])){

    echo "emailExist";
    exit();
    }
    if($login->initWithUsername($_POST['username'])){

        echo "usernameExist";
        exit();
    }
    $username = $_POST["username"];
    $password = $_POST["password"];
    $email = $_POST["email"];
    $name = $_POST["name"];
  $tempUser = new Users();
  $tempUser->setEmail($email);
  $tempUser->setPassword($password);
  $tempUser->setUsername($username);
  $tempUser->setName($name);
  if($tempUser->registerUser()){
      echo "suc";
      exit();
  }else{
      echo "fail";
      exit();
  }



}
?>