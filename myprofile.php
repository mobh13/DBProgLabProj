<?php require_once "includes/bundle.php"?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Videos</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/styles.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"

            crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

</head>

<body>
<?php include 'login.html';?>
<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">

            <div class="col-4 text-center">
                <a class="card-link text-dark" href="index.php"><img src="assets/logo.jpg" style="height: 120px"/> </a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">

                <?php
                $login = new Login();
                $error = false;
                $errorMsg = null;
                if(!$login->check_session()){
die("Error you dont belong to here");}


                else{
                    $user = new Users();
                    $user->initWithId($_SESSION["id"]);
                    if (isset($_POST["update"]) ){

                        if ($_POST["name"] == "" || $_POST["password"] == "" ||  $_POST["username"] == "" || $_POST["email"] == ""){

                            $error = true;
                            $errorMsg[] ="all fields are required";
                        }else{

                            if(preg_match("!image!",$_FILES["avatar"]["type"]) && $_FILES["avatar"]["size"] > 0){

                                $upload = new UploadFiles();
                                if( !$upload->uploadDir("upload/")) {
                                    $error = true;
                                }
                                 if(! $errors = empty($upload->upload("avatar"))){
                                     $errorMsg = $errors;
                                 }

                                $avatar = $upload->getUploadDir().$upload->getFilepath();
                                if(empty($errorMsg)){
                                    unlink($user->getAvatar());
                                    $user->setAvatar($avatar);
                                }else{
                                    $error = true;
                                }

                            }elseif(!preg_match("!image!",$_FILES["avatar"]["type"]) && $_FILES["avatar"]["size"] > 0 ){
$error = true;
$errorMsg[] = "Please upload a valid avatar";
                            }
                            if(!$error){

                                $user->setName($_POST["name"]);
                                $user->setPassword($_POST["password"]);
                                $user->updateDB();
                            }
                        }

                    }


                    ?>
                    <a href="myprofile.php"><img src="<?php echo $user->getAvatar();?> " class="rounded-circle avatar mr-2"/> </a>
                    <div class="col-md-5 justify-content-center align-content-center text-center">  <a  href="uploadvid.php" class="btn btn-sm btn-info mb-1"> Upload Video</a>
                        <a  href="logout.php" class="btn btn-sm btn-secondary">Logout</a>
                    </div>

                    <?php
                }
                ?>    </div>
        </div>
    </header>


    <div class="row">
        <div class="col-12">
            <hr/>
        </div>
    </div>

</div>

<main role="main" class="container">
    <div class="row">
        <div class="col-md-10">
            <div class="row mb-1">
                <div class="col-12">
                    <h5>My Profile</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-3"><!--left col-->


                    <div class="text-center">
                        <img src="<?php echo $user->getAvatar();?>" class="avatar rounded-circle " alt="avatar">

                    </div></hr><br>








                </div><!--/col-3-->
                <div class="col-sm-9">
                    <?php if($error){
                       ?>
                        <p class="alert alert-danger"><?php foreach($errorMsg as $errorMsg ) { print ($errorMsg ."\n");}?></p>
                        <?php
                    }?>

                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#home"
                               role="tab" aria-controls="My info" aria-selected="true">My info</a>
                            <a class="nav-item nav-link" id="nav-Register-tab" data-toggle="tab"
                               href="#editMyInfo" role="tab" aria-controls="editMyInfo" aria-selected="false">Edit Profile</a>
                            <a class="nav-item nav-link" id="nav-Register-tab" data-toggle="tab"
                               href="#myVids" role="tab" aria-controls="myVids" aria-selected="false">My Videos</a>
                        </div>
                    </nav>

                    <div class="tab-content">
                        <div class="tab-pane active justify-content-center text-center align-content-center " id="home">
<br>
                            <h4 class="align-self-center ">Hello <?php echo $user->getName();?></h4>

                        </div><!--/tab-pane-->
                        <div class="tab-pane" id="editMyInfo">

                            <h2></h2>

                            <hr>
                            <form class="form" action="" method="post" id="updateProfile" enctype="multipart/form-data">
                                <div class="form-group">

                                    <div class="col-xs-12">
                                        <label for="name"><h4>Name</h4></label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="enter your Name" title="enter your name." value="<?php echo $user->getName();?>" required>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="col-xs-12">
                                        <label for="username"><h4>username</h4></label>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="enter your Username" title="enter your username." value="<?php echo $user->getUsername();?>" readonly>
                                    </div>
                                </div>


                                <div class="form-group">

                                    <div class="col-xs-12">
                                        <label for="email"><h4>Email</h4></label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="you@email.com" title="enter your email." value="<?php echo $user->getEmail();?>" readonly>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="col-xs-12">
                                        <label for="password"><h4>Password</h4></label>
                                        <input type="password" class="form-control" name="password" id="password" placeholder="password" title="enter your password." value="<?php echo $user->getPassword();?>"required>
                                    </div>
                                </div>

                                <div class="form-group">

                                    <div class="col-xs-12">
                                        <label for="avatar"><h4>Avatar</h4></label>
                                        <input type="file" class="form-control" name="avatar" id="avatar" placeholder="avatar" title="upload your avatar.">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <br>
                                        <button class="btn btn-lg btn-info" type="submit" name="update"><i class="glyphicon glyphicon-ok-sign"></i> Save</button>
                                        <button class="btn btn-lg btn-secondary" type="reset"><i class="glyphicon glyphicon-repeat"></i> Reset</button>
                                    </div>
                                </div>
                            </form>

                        </div><!--/tab-pane-->
                        <div class="tab-pane" id="myVids">
<br/>


                            <table class="table table-hover table-striped">
                                <thead class="bg-info text-light">

                                <th>thumbile</th>
                                <th>Title</th>
                                <th>views</th>
                                <th>Date</th>

                                </thead>
                                <tbody>
                                <?php
                                    $videos = new video();
                                    $vids = $videos->getVideosByUser($user->getId());

                                    foreach ($vids as $vid){

                                    ?>
                                    <tr>

                                        <td><a href="showvid.php?id=<?php echo $vid->id;?>"><img src=" <?php echo $vid->thumbnail; ?>" class="avatar"/></a></td>
                                        <td><a href="showvid.php?id=<?php echo $vid->id;?>"><?php echo $vid->title; ?></a></td>
                                        <td><?php echo  number_format($vid->views);?></td>
                                        <td><?php echo  $vid->date;?></td>
                                    </tr>

                                    <?php
                                } ?>
                                </tbody>
                            </table>                        </div>

                    </div><!--/tab-pane-->
                </div><!--/tab-content-->


        </div>
    </div>
    </div>



</main><!-- /.container -->



</body>

</html>