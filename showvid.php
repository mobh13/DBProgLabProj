<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    require_once 'includes/bundle.php';
    $sameUser = false;
    $alreadyLiked = false;
    $loogedIn = false;
    $likeType = null;
    $login = new login();
    $video = new Video();
    $comments = new Comments();
    if (isset($_GET["id"]) && is_numeric($_GET["id"])) {

        if ($video->check($_GET["id"])) {
            $loogedIn = $login->check_session();
        if ( isset($_GET["addLike"] ) && is_numeric($_GET["id"]) && $loogedIn){
$like = new Likes();
            if($like->existWithUserid($video->getId(),$_SESSION["id"])[0]){

                $like->initWithVideoidAndUserid($video->getId(),$_SESSION["id"]);

                if($_GET["addLike"] == 1){
                    $like->setType(1);
                    $like->updateDB();
                }elseif ($_GET["addLike"] == 2){
                    $like->setType(2);
                    $like->updateDB();

                }

            }else{
                if($_GET["addLike"] == 1 or $_GET["addLike"] == 2 ){
print_r($like->existWithUserid($video->getId(),$_SESSION["id"]));
                    $like->setType($_GET["addLike"]);
                    $like->setUserid($_SESSION["id"]);
                    $like->setVideoid($video->getId());
                    $like->addToDB();
                }

            }

        }elseif ( isset($_GET["removeLike"] ) && is_numeric($_GET["id"]) && $loogedIn){
            $like = new Likes();
            $like->initWithVideoidAndUserid($_GET["id"],$_SESSION["id"]);
            $like->deleteLike();
        }
            $video->updateViews();
            $likes = new Likes();
            $noOfLikes = $likes->getLikes($video->getId());
            $noOfDisLikes = $likes->getDisLikes($video->getId());
            $videoOwner = new Users();
            $videoOwner->initWithId($video->getUserid());

            if ($loogedIn) {

                if ($videoOwner->getId() == $_SESSION["id"]) {

                    $sameUser = true;
                } else {
                    $result = $likes->existWithUserid($video->getId(), $_SESSION["id"]);
                    if ($result[0]) {
                        $alreadyLiked = true;
                        $likeType = $result[1];

                    }
                }

                if(isset($_POST["submit"])){

                    if($_POST["comment"] == "" or strlen($_POST["comment"]) < 10){

                        $error = "the comment must be not empty and  at least 10 chars  ";
                    }else{

                        $comm = new Comments();
                        $comm->setVidid($video->getId());
                        $comm->setContent($_POST["comment"]);
                        $comm->setDate(date('Y-m-d H:i:s'));
                        $comm->setUserid($_SESSION["id"]);
                        if(isset($_POST["parentComment"]) && $_POST["parentComment"] > 0 && is_numeric($_POST["parentComment"]) ){

                            $comm->setParentid($_POST["parentComment"]);
                        }
                        $comm->addToDB();
                    }

                }

            }
            $comments->setVidid($video->getId());

        } else {


            echo "Error: video id is not valid";
            die();
        }

    } else {

        echo "Error: no video is selected";
        die();

    }
    ?>
    <meta charset="UTF-8">
    <title>My Videos</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="assets/css/video-js.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css"
          integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/styles.css"/>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/video.min.js"></script>
    <script>
        function setParentComment($id) {


            document.getElementById('parentComment').value = $id;
        }

    </script>

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


</div>

<main role="main" class="container">
    <div class="row">
        <div class="col-md-12">

            <div class="row mb-2 mt-2">
                <video id='my-video' class='video-js col-12' controls preload='auto'
                       data-setup='{}'>
                    <source src='<?php echo $video->getVidurl(); ?>'>

                </video>
            </div>
            <div class="row mb-1">
                <div class="col-12">
                    <h6> <?php echo $video->getTitle(); ?> </h6>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-2"><p class="text-muted"> <?php echo number_format($video->getViews()); ?> Views</p></div>
                <?php
                if (!$loogedIn or $sameUser) {

                    ?>
                    <div class="col-md-1 ml-auto">
                        <div class="row"><a><i class="fas fa-thumbs-up fa-2x"></i></a>
                            <p class="text-muted"><?php echo $noOfLikes; ?></p></div>
                    </div>
                    <div class="col-md-1 ">
                        <div class="row"><a><i class="fas fa-thumbs-down fa-2x"></i></a>
                            <p class="text-muted"><?php echo $noOfDisLikes; ?></p></div>
                    </div>
                    <?php
                } elseif ($loogedIn && !$alreadyLiked && !$sameUser) {


                    ?>
                    <div class="col-md-1 ml-auto">
                        <div class="row"><a href="?id=<?php echo $video->getId();?>&addLike=1"><i class="far fa-thumbs-up fa-2x"></i></a>
                            <p class="text-muted"><?php echo $noOfLikes; ?></p></div>
                    </div>
                    <div class="col-md-1 ">
                        <div class="row"><a href="?id=<?php echo $video->getId();?>&addLike=2"><i class="far fa-thumbs-down fa-2x"></i></a>
                            <p class="text-muted"><?php echo $noOfDisLikes; ?></p></div>
                    </div>
                    <?php
                } elseif ($loogedIn && $alreadyLiked && !$sameUser) {

                    if ($likeType == 1) {
                        ?>
                        <div class="col-md-1 ml-auto">
                            <div class="row"><a href="?id=<?php echo $video->getId();?>&removeLike="><i class="fas fa-thumbs-up fa-2x"></i></a>
                                <p class="text-muted"><?php echo $noOfLikes; ?></p></div>
                        </div>
                        <?php
                    } else {

                        ?>
                        <div class="col-md-1 ml-auto ">
                            <div class="row"><a href="?id=<?php echo $video->getId();?>&addLike=1"><i class="far fa-thumbs-up fa-2x"></i></a>
                                <p class="text-muted"><?php echo $noOfLikes; ?></p></div>
                        </div>
                        <?php

                    }
                    if ($likeType == 2) {
                        ?>
                        <div class="col-md-1 ">
                            <div class="row"><a href="?id=<?php echo $video->getId();?>&removeLike="><i class="fas fa-thumbs-down fa-2x"></i></a>
                                <p class="text-muted"><?php echo $noOfDisLikes; ?></p></div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="col-md-1 ">
                            <div class="row"><a href="?id=<?php echo $video->getId();?>&addLike=2"><i class="far fa-thumbs-down fa-2x"></i></a>
                                <p class="text-muted"><?php echo $noOfDisLikes; ?></p></div>
                        </div>
                        <?php
                    }
                }
                ?>
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-1"><a href="profile.php?id=<?php echo $videoOwner->getId(); ?>"><img
                                src="<?php echo $videoOwner->getAvatar(); ?>" class="rounded-circle avatar"> </a></div>
                <div class="col-md-1 align-self-center ">
                    <div class="row"><a href="profile.php?id=<?php echo $videoOwner->getId(); ?>"
                                        class="text-dark font-weight-bold  align-self-center"> <?php echo $videoOwner->getName(); ?> </a>
                    </div>
                    <div class="row"><p class="text-muted small"><?php echo time_elapsed_string($video->getDate()); ?></p></div>
                </div>
            </div>
            <div class="row ">
                <div class="offset-1 col-md-10"><p class="text-justify"> <?php echo $video->getDescreption() ?></p></div>
            </div>
<?php $files = new Files();
$files->setVidid($video->getId());
if($files->checkForFiles() > 0){
    $counter =1
  ?> <div class="row">
        <div class="col-md-12">
            <hr>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
<h4>Files:</h4>
        </div>
    <ul>

            <?php

    foreach ($files->getVideoFiles() as $file){

        ?>
<li><a class="" href="<?php echo $file->fileurl;?>">File-<?php echo $counter; ?></a></li>
            <?
        $counter++;
    }?>
    </ul></div><?php
}
?>



            <div class="row">
                <div class="col-md-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <p class="font-weight-bold text"> <?php echo number_format($comments->getCommentCount());?> Comments</p>
                </div>
            </div>
            <?php if($loogedIn){
                ?>
            <?php if ($error !=""){?>
                <div class="alert alert-danger"><?php echo $error;?></div><? }?>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <form class="form" method="post">
                            <div class="form-row">
                            <textarea class=" form-control-plaintext" name="comment"
                                      placeholder="Add Comment" id="comment"></textarea>

                            </div>
                            <input name="parentComment" type="hidden" value ="0" id="parentComment">
                            <div class="form-row mt-2">
                                <button class="btn btn-primary" name="submit" type="submit"> Publish Comment</button>

                            </div>
                        </form>
                    </div>
                </div>

                <?php
            }?>

            <?php

            $id = $_GET["id"];

$db = DB::getInstance();
            $dbc = $db->dblink;

            $display = 6;
            $pages = null;

            if(isset($_GET['p']) )
            {
                $pages=$_GET['p'];
            }
            else
            {
                $q = "select count(*) from labProj_comments  where vidid = '$id' and parentid = 0";
                $r = mysqli_query($dbc, $q);
                $row = mysqli_fetch_array($r, MYSQLI_NUM);
                $records=$row[0];


                if($records > $display ){
                    $pages=ceil($records/$display);
                }
                else{
                    $pages = 1;
                }
            }

            if(isset($_GET['s']) )
                $start=$_GET['s'];
            else
                $start = 0;

            $q = "select * from labProj_comments  where vidid = '$id'  and parentid = 0  limit $start, $display";
            $r = mysqli_query($dbc, $q);
            $res = mysqli_fetch_array($r);

            $r = mysqli_query($dbc, $q);
            while($res = mysqli_fetch_array($r)){

                $users= new Users();

                $users->initWithId($res["userid"]);
                ?>
                <div class="row mb-3 ">
                    <div class="col-md-12">
                        <hr>
                        <div class="row mb-2">
                            <div class="col-md-1"><a href="profile.php?id=<?php echo $users->getId();?>"><img src="<?php echo $users->getAvatar();?>" class="rounded-circle avatar"> </a></div>
                            <div class="col-md-2 align-self-center ">
                                <div class="row mb-0"><a href="profile.php?id=<?php echo $users->getId();?>" class="text-dark font-weight-bold  align-self-center">
                                        <?php echo $users->getUsername();?> </a></div>
                                <div class="row"><p class="text-muted small font-weight-bold"><?php echo time_elapsed_string($res["date"]);?></p></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">
                                <p><?php echo $res["content"];?>

                                </p>
                            </div>
                        </div>
                        <div class="text-right float-right"><a href="#comment" onclick="return setParentComment(<?php echo $res["id"];?>);">Comment on this</a></div>

                    </div>
                </div>
            <?php  $comments->setId($res["id"]);

                    if($comments->checkForSubComments()){


                        $subComments = $comments->getSubComments();

                        foreach ($subComments as $subCom) {
                            $users= new Users();

                            $users->initWithId($subCom->userid);
                        ?>

                        <div class="row mb-3 ">
                            <div class="col-md-11 ml-3">
                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-1"><a href="profile.php?id=<?php echo $users->getId();?>"-"><img src="<?php echo $users->getAvatar();?>" class="rounded-circle avatar"> </a></div>
                                    <div class="col-md-2 align-self-center ">
                                        <div class="row mb-0"><a href="profile.php?id=<?php echo $users->getId();?>" class="text-dark font-weight-bold  align-self-center">
                                                <?php echo $users->getUsername();?> </a></div>
                                        <div class="row"><p class="text-muted small font-weight-bold"><?php echo time_elapsed_string($subCom->date);?></p></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <p><?php echo $subCom->content;?>

                                        </p>
                                    </div>
                                </div>
                                <div class="text-right float-right"><a href="#comment" onclick=" return setParentComment(<?php echo $subCom->id;?>)">Replay on this</a></div>

                            </div>
                        </div>
            <?php
                            $comments->setId($subCom->id);
                            if($comments->checkForSubComments()){

                                $sSComments = $comments->getSubComments();
                                foreach ($sSComments as $ssComment){
                                    $users= new Users();

                                    $users->initWithId($ssComment->userid);
                                    ?>
                                    <div class="row mb-3 ">
                                        <div class="col-md-10 ml-5">
                                            <hr>
                                            <div class="row mb-2">
                                                <div class="col-md-1"><a href="profile.php?id=<?php echo $users->getId();?>"-"><img src="<?php echo $users->getAvatar();?>" class="rounded-circle avatar"> </a></div>
                                                <div class="col-md-2 align-self-center ">
                                                    <div class="row mb-0"><a href="profile.php?id=<?php echo $users->getId();?>" class="text-dark font-weight-bold  align-self-center">
                                                            <?php echo $users->getUsername();?> </a></div>
                                                    <div class="row"><p class="text-muted small font-weight-bold"><?php echo time_elapsed_string($ssComment->date);?></p></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-11">
                                                    <p><?php echo $ssComment->content;?>

                                                    </p>
                                                </div>
                                            </div>


                                        </div>
                                    </div>            <?php

                                }

                            }
                        }

                    }}
            ?>
            <div class="row justify-content-center"> <?php
                if($pages > 1)
                {
                    echo '     <nav >
                    <ul class="pagination">'  ;

                    $currentpage = ($start/$display)+1;

                    if($currentpage != 1)
                    {


                        echo '<li class="page-item">
                            <a class="page-link" href="showvid.php?$s=' . ($start - $display) .
                            '&p=' .$pages .'&id='.$id.'" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Previous</span>
                            </a>
                        </li>';
                    }
                    for($i = 1; $i <= $pages; $i++)
                    {
                        if($i == $currentpage){

                            echo '   <li class="page-item active"><a class="page-link" href="#">&nbsp' . $i . '&nbsp</a></li>';
                        }
                        if($i != $currentpage)
                        {


                            echo '<li class="page-item"><a class="page-link" href="showvid.php?s=' . (($display * ($i-1))) . '&p='
                                . $pages .'&id='.$id.'">&nbsp' . $i . '&nbsp</a></li>
';
                        }
                    }
                    if($currentpage != $pages)
                    {

                        echo '<li class="page-item">
                            <a class="page-link" href="showvid.php?s=' . ($start+$display) . '&p=' . $pages
                            .'&id='.$id.'" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>';
                    }
                    echo '   </ul>
                </nav>';
                }
                ?></div>

                </div>



        </div>


    </div>
</main><!-- /.container -->


</body>

</html>