<?php
require "includes/bundle.php";
$login = new Login();

if(isset($_POST['login']))
{
$email = $_POST["email"];
$password = $_POST["password"];
if($_POST["email"] == "" or $_POST["password"] == ""){

    die("empty");
}else{

    if (!$login->login($email,$password)){
        echo "fail";
        exit();
    }else{
        echo "suc";
        exit();
    }
}


}
if(isset($_POST['register']))
{
    $login->setEmail($_POST["email"]);
    $login->setUsername($_POST["username"]);
    if( $_POST["username"] ==""||$_POST["password"] =="" || $_POST["email"] =="" || $_POST["name"] ==""){
        echo "empty";
        die();
    }
    if($login->checkWithEmail()){

    echo "emailExist";
    exit();
    }
    if($login->checkWithUsername()){

        echo "usernameExist";
        die();
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
    if(preg_match("!image!",$_FILES["avatar"]["type"]) && $_FILES["avatar"]["size"] > 0){

        $upload = new UploadFiles();
        if( !$upload->uploadDir("upload/")) {
            die("fail");
        }
        if(!empty($errors = ($upload->uploadForRegister("avatar")))){
           die(print_r($errors));
        }else{
            $avatar = $upload->getUploadDir().$upload->getFilepath();
            $tempUser->setAvatar($avatar);

        }





    }elseif(!preg_match("!image!",$_FILES["avatar"]["type"]) && $_FILES["avatar"]["size"] > 0 ){
        die("avatarNotValid");
    }elseif($_FILES["avatar"]["size"] < 0){
        $tempUser->setAvatar("assets/user.png");
    }


  if($tempUser->registerUser()){
      $login->login($email,$password);
      echo "suc";
      exit();
  }else{
      echo "fail";
      exit();
  }



}
?>