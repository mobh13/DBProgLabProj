<?php require "includes/bundle.php"?>
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

<body class="" style="background: ">
<?php include 'login.html';?>
<div class="container" >
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">

            <div class="col-4 text-center">
                <a class="card-link text-dark" href="index.php"><img src="assets/logo.jpg" style="height: 120px"/> </a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">

                <?php
                $login = new Login();
                if(!$login->check_session()){

                ?>
                <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal" data-target="#exampleModal">
                    Sign up/Login
                </button>    <?php    }
                else{
                    $user = new Users();
                    $user->initWithId($_SESSION["id"]);
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

    <div class="nav-scroller py-1 mb-3 " style="background:  #da575b"  >
        <nav class="nav d-flex justify-content-center  ">
            <?php $category = new Category();
            $categories= $category->getAllCategories();
            foreach ($categories as $cat){?>
                <a class="pl-5 pr-5 p-2" style="color: #ffffff" href="cat.php?id=<?php echo $cat->id ;?>"><?php echo $cat->title ;?></a>

<?php }
            ?>
        </nav>
    </div>


    <div class="row mb-3">
        <div class="col-12">
           <form action="search.php">

               <input class="form-control text-center " style="background:#FCFCFC "  type="search"  placeholder="To search Write your text here and press enter" name="search">
           </form>
        </div>
    </div>
</div>

<main role="main" class="container">
    <?php
    $login = new Login();
    if($login->check_session()){

    ?>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-1">
                <div class="col-12">
                  <h6 class="alert alert-info"> Welcome <?php echo $user->getUsername(); ?> !</h6>
                </div>
            </div>
            <?php }?>
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-1">
            <div class="col-12">
                <h5>Latest Videos</h5>
            </div>
        </div>
            <div class="row mb-2 justify-content-center">
            <?php
            $videos = new video();
            $latest = $videos->getLatestVideos(9);
            foreach ($latest as $vid){

?>
                <div class=" card col-md-3  p-0 m-1" style="background: #ececec; color: #142d4c" >
                    <a href="showvid.php?id=<?php echo $vid->id;?>"> <img class="card-img-top" src="<?php echo $vid->thumbnail;?>" alt="Card image cap"></a>
                    <div class="card-body mb-0">
                        <a href="showvid.php?id=<?php echo $vid->id;?>">   <h5 class="card-title mb-0"><?php echo $vid->title;?></h5></a>
                        <a href="profile.php?id=<?php echo $vid->userid;?>">     <p class=" card-subtitle  small text-muted mb-0">@<?
                                $user = new Users();
                                $user->initWithId($vid->userid);
                                echo $user->getUsername();?></p></a>
                        <p class="small text-muted mb-0"><?php echo number_format($vid->views);?>Views <b class="align-self-center">.</b> <?php echo time_elapsed_string($vid->date);?></p>
                    </div>
                </div>
            <?php

            }
            ?>






            </div>
            <div class="row mb-1">
                <div class="col-12">
                    <h5>Popular Videos</h5>
                </div>
            </div>
            <div class="row mb-2 justify-content-center">
                <?php
                $videos = new video();
                $latest = $videos->getTopVideos(9);
                foreach ($latest as $vid){

                    ?>
                    <div class=" card col-md-3  p-0 m-1" style="background: #ececec; color: #142d4c" >
                        <a href="showvid.php?id=<?php echo $vid->id;?>"> <img class="card-img-top" src="<?php echo $vid->thumbnail;?>" alt="Card image cap"></a>
                        <div class="card-body mb-0">
                            <a href="showvid.php?id=<?php echo $vid->id;?>">   <h5 class="card-title mb-0"><?php echo $vid->title;?></h5></a>
                            <a href="profile.php?id=<?php echo $vid->userid;?>">     <p class=" card-subtitle  small text-muted mb-0">@<?
                                $user = new Users();
                                $user->initWithId($vid->userid);
                                    echo $user->getUsername();?></p></a>
                            <p class="small text-muted mb-0"><?php echo number_format($vid->views);?>Views <b class="align-self-center">.</b> <?php echo time_elapsed_string($vid->date);?></p>
                        </div>
                    </div>
                    <?php

                }
                ?>






            </div>


        </div>
    </div>




</main><!-- /.container -->



</body>

</html>