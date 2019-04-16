<!DOCTYPE html>
<html lang="en">
<?php
require_once "includes/bundle.php";

$video = new Video();
$uploadFiles = new UploadFiles();
$errorMsg = null;


if (isset($_POST["upload"])) {

    if ($_FILES["vidfile"]["name"] == "") {

        $errorMsg[] = " no file is selected\n";
    }

    if ($_FILES["vidfile"]["size"] > 52428800) {

        $errorMsg[] = "video size is over the limit\n";
    }

    $fileExtension = explode('.', $_FILES["vidfile"]["name"]);
    $fileExtension = end($fileExtension);
    $fileExtension = strtolower($fileExtension);
    if (strtolower($fileExtension) != "mp4") {


        $errorMsg[] = "video extension is not supported";

    }
    if ($_FILES["thumb"]["name"] == "") {

    $errorMsg[] = " no file is selected for thumbnail\n";
}

    if ($_FILES["thumb"]["size"] > 52428800) {

        $errorMsg[] = "thumbnail size is over the limit\n";
    }


    if(!preg_match("!image!",$_FILES["thumb"]["type"])){

    $errorMsg[] = "thumbnail format is not supported\n";
    }

    if ($_POST["title"] == "" || $_POST["description"] == "" || $_POST["category"] == "") {

        $errorMsg[] = "title and description are required \n";
    }


    $errorMsg = array_filter($errorMsg);
    if (empty($errorMsg)) {


        if (!$uploadFiles->uploadDir("vids/")) {
            $errorMsg[] = "error please contact admin\n";
        }

        if (!$errors = empty($uploadFiles->upload("vidfile"))) {
            $errorMsg = array_merge($errorMsg, $errors);
        }

        $vidPath = $uploadFiles->getUploadDir() . $uploadFiles->getFilepath();
    }

    $errorMsg = array_filter($errorMsg);
    if (empty($errorMsg)) {


        if (!$uploadFiles->uploadDir("thumb/")) {
            $errorMsg[] = "error please contact admin\n";
        }

        if (!$errors = empty($uploadFiles->upload("thumb"))) {
            $errorMsg = array_merge($errorMsg, $errors);
        }

        $thumb = $uploadFiles->getUploadDir() . $uploadFiles->getFilepath();
    }
    if (empty($errorMsg)) {

        $video->setUserid($_SESSION["id"]);
        $video->setDate(date('Y-m-d H:i:s'));
        $video->setDescreption($_POST["description"]);
        $video->setTitle($_POST["title"]);
        $video->setThumbile($thumb);
        $video->setVidurl($vidPath);
        $video->addToDB();
        $count = 1;
        $files;
        while ($count <= 3) {

            $file = "file" . $count;
            if ($_FILES[$file]["size"] > 10) {

                $files[] = $file;
            }
            $count++;
        }

        if (!empty($files)) {

            foreach ($files as $f) {
                if ($_FILES[$file]["size"] > 52428800) {

                    $errorMsg[] = "file size is over the limit";
                }

                if (!$uploadFiles->uploadDir("files/")) {
                    $errorMsg[] = "error uploading extra files please contact admin";
                }
                if (!$errors = empty($uploadFiles->upload($f))) {
                    $errorMsg = array_merge($errorMsg, $errors);
                }

                $filepath = $uploadFiles->getUploadDir() . $uploadFiles->getFilepath();
                $files = new Files();
                $files->setFileurl($filepath);
                $video->getIdAfterAdd();
                $files->setVidid($video->getId());
                $files->addToDB();

            }
        }

    }

    $errorMsg = array_filter($errorMsg);
}

?>
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
    <script src="assets/js/tinymce/tinymce.min.js"></script>
    <script>tinymce.init({
            selector: '#description',
            plugins: 'print preview   searchreplace autolink directionality  visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount   imagetools textpattern ',
            toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
            image_advtab: true
        });</script>


</head>

<body>
<?php include 'login.html'; ?>
<div class="container">
    <header class="blog-header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">

            <div class="col-4 text-center">
                <a class="card-link text-dark" href="index.php"><img src="assets/logo.jpg" style="height: 120px"/> </a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">

                <?php
                $login = new Login();
                if (!$login->check_session()) {

                    ?>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-toggle="modal"
                            data-target="#exampleModal">
                        Sign up/Login
                    </button>    <?php } else {
                    $user = new Users();
                    $user->initWithId($_SESSION["id"]);
                    ?>
                    <a href="myprofile.php"><img src="<?php echo $user->getAvatar(); ?> "
                                                 class="rounded-circle avatar mr-2"/> </a>
                    <div class="col-md-5 justify-content-center align-content-center text-center"><a
                                href="uploadvid.php" class="btn btn-sm btn-info mb-1"> Upload Video</a>
                        <a href="logout.php" class="btn btn-sm btn-secondary">Logout</a>
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
                    <h5>Upload Video</h5>
                </div>
            </div>
            <div class="row m-2">
                <div class="col-12">
                    <?php if (empty($errorMsg) && isset($_POST["upload"])) {
                        ?>
                        <p class="alert alert-success" id="success">Video was uploaded</p>

                        <?php

                    } elseif (!empty($errorMsg) && isset($_POST["upload"])) {
                        ?>
                        <p class="alert alert-danger" id="error"><?php foreach ($errorMsg as $error) {
                                echo $error . "\n";
                            } ?></p>

                        <?php

                    } ?>

                </div>
            </div>
            <div class="row mt-3" id="form">

                <form target="_self" method="Post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="title">Title</label>
                                    <input type="text" class="form-control" name="title" id="title"
                                           placeholder="Video title" title="enter your Video title.">
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description"
                                              placeholder="Video description"
                                              title="enter your Video description."></textarea>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="category">Category</label>
                                    <select class="form-control" name="category" id="category">
                                        <?php $category = new Category();
                                        $categories = $category->getAllCategories();
                                        $count = 0;
                                        foreach ($categories as $cat) {
                                            ?>

                                            <option class="form-control"
                                                    value="<?php echo $cat->id; ?>" <?php if ($count = 0) {
                                                echo "selected";
                                            } ?>><?php echo $cat->title; ?></option>

                                            <?php $count++;
                                        }
                                        ?>

                                    </select></div>
                            </div>


                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="vidfile">Video File</label>
                                    <input type="file" class=" form-control-file" name="vidfile" id="vidfile">
                                    <p class="text-warning"> Please Note that the max size for the video is 50mb.</p>
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="thumb">Thumbnail</label>
                                    <input type="file" class=" form-control-file" name="thumb" id="thumb">
                                    <p class="text-warning"> Please Note that the max size for the thumbnail is 50mb.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <br>
                                    <input type="hidden" name="upload"/>
                                    <button class="btn btn-lg btn-primary" id="uploadButton" type="submit"><i
                                                class="fas fa-arrow-circle-up"></i> upload
                                    </button>
                                    <button class="btn btn-lg btn-secondary" type="reset"><i class="fas fa-redo"></i>
                                        Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for=""><h4>Files Associated with the video</h4></label>
                                    <p class="text-warning"> Please Note that the max size for each file is 50mb.</p>

                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="file1">File 1</label>
                                    <input type="file" class="form-control" name="file1" id="file1">
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="file2">File 2</label>
                                    <input type="file" class="form-control" name="file2" id="file2">
                                </div>
                            </div>
                            <div class="form-group">

                                <div class="col-md-12">
                                    <label for="file3">File 3</label>
                                    <input type="file" class="form-control" name="file3" id="file3">
                                </div>
                            </div>
                        </div>
                    </div>


                </form>

            </div>


        </div>
    </div>


</main><!-- /.container -->


</body>

</html>