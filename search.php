<?php  require_once 'includes/bundle.php'; require "includes/Search.php";?>
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
    <?php


    $db = DB::getInstance();

    if(isset($_GET["search"])){
$search = trim($_GET["search"]);


        $dbc = $db->dblink;

        $display = 6;
        $pages = null;

        if(isset($_GET['p']) )
        {
            $pages=$_GET['p'];
        }
        else
        {
            $q = 'select count(*) from labProj_videos where match(descreption, title) against (\''. $search . '\')';
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

        $q = "select * from labProj_videos where match(descreption, title) against ('" . $search . "') order by views DESC limit ".$start.', '.$display;
        $r = mysqli_query($dbc, $q);
        $res = mysqli_fetch_array($r);
        $r = mysqli_query($dbc, $q);
    }
    ?>
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


</div>

</div>

<main role="main" class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="row mb-1">
                <div class="col-12">
                    <h5>Search <?php if (isset($_GET["search"])){echo " results";}?></h5>
                </div>
            </div>
            <div class="row m-3">
                <div class="col-md-12"><form>                <input class="form-control text-center " style="background:#FCFCFC "  type="search"  placeholder="To search Write your text here and press enter" name="search">
                    </form></div>
            </div>
            <?php  if (!empty($res) &&isset($_GET["search"])){while($result = mysqli_fetch_array($r)){

                ?>
                <div class="row mb-2 justify-content-center">


                    <div class="col-md-9 border rounded">
                        <div class="row m-2">

                            <div class="col-md-2"><a href="showvid.php?id=<?php  echo $result["id"]; ?>"><img
                                        src="<?php echo $result["thumbnail"]; ?>" class="rounded avatar"> </a></div>
                            <div class="col-md-8 align-self-center ">
                                <div class="row"><a href="showvid.php?id=<?php  echo $result["id"]; ?>"
                                                    class="text-dark font-weight-bold  align-self-center"> <?php  echo $result["title"]; ?> </a>
                                </div>
                                <div class="row"><p class="text-muted small"><?php echo $result["views"]; ?> Views -<?php echo time_elapsed_string($result["date"]); ?></p></div>
                            </div>
                        </div>
                    </div>



                </div>

                <?php
            }}elseif(empty($res) && isset($_GET["search"])){
                ?>

                <div class="row mb-2 justify-content-center">


                    <div class="col-md-12  text-center">
                        <div class="row m-2 text-center">

                     <h3> No results found</h3>
                            </div>
                        </div>
                    </div>



                </div>

                <?php
            }?>

            <div class="row justify-content-center">
                <?php
                if($pages > 1)
                {
                    echo '     <nav >
                    <ul class="pagination">'  ;

                    $currentpage = ($start/$display)+1;

                    if($currentpage != 1)
                    {


                        echo '<li class="page-item">
                            <a class="page-link" href="search.php?$s=' . ($start - $display) .
                            '&p=' .$pages .'&search='.$search.'" aria-label="Previous">
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


                            echo '<li class="page-item"><a class="page-link" href="search.php?s=' . (($display * ($i-1))) . '&p='
                                . $pages .'&search='.$search.'">&nbsp' . $i . '&nbsp</a></li>
';
                        }
                    }
                    if($currentpage != $pages)
                    {

                        echo '<li class="page-item">
                            <a class="page-link" href="search.php?s=' . ($start+$display) . '&p=' . $pages
                            .'&search='.$search.'" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                            </a>
                        </li>';
                    }
                    echo '   </ul>
                </nav>';
                }
                ?>



            </div>

        </div>
    </div>




</main><!-- /.container -->



</body>

</html>