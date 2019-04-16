<?php
require_once "includes/bundle.php";

$video = new Video();
$uploadFiles = new UploadFiles();


if(isset($_POST["upload"])){

    if($_FILES["vidfile"]["name"] == ""){

        die("no file");
    }
    if ($_FILES["vidfile"]["size"] > 52428800){

        die("size");
    }
//    if ($_FILES["vidfile"]["ext"] ="sss"){
//
//        die("format");
//    }
//    if ($_POST["title"] =="" || $_POST["description"] =="" || $_POST["category"] ==""){
//
//        die("empty");
//    }

    if( !$uploadFiles->uploadDir("vids/")) {
        die("error");
    }
    if(!$errors = empty($uploadFiles->upload("vidfile"))){
        die(print_r($errors));
    }

    $vidPath = $uploadFiles->getUploadDir().$uploadFiles->getFilepath();

    if(empty($errorMsg)){
$video->setUserid($_SESSION["id"]);
$video->setDate(  date('Y-m-d H:i:s'));
$video->setDescreption($_POST["description"]);
$video->setTitle($_POST["title"]);
$video->setVidurl($vidPath);
$video->addToDB();

    }else{
        die("fail")    ;    }
    $count = 1;
    $files ;
    while ($count <= 3){

        $file = "file".$count;
        if ($_FILES[$file]["size"] > 10){

          $files[] = $file;
        }
        $count++;
    }
    if(!empty($files)){

        foreach ( $files as $f){
            if( !$uploadFiles->uploadDir("files/")) {
                die("error");
            }
            if(!$errors = empty($uploadFiles->upload($f))){
                die(print_r($errors));
            }

            $filepath = $uploadFiles->getUploadDir().$uploadFiles->getFilepath();
            $files = new Files();
            $files->setFileurl($filepath);
            $video->getIdAfterAdd();
            $files->setVidid($video->getId());
            $files->addToDB();

        }
    }

}

?>
