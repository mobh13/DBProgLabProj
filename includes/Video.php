<?php
/**
 * Created by PhpStorm.
 * User: madan
 * Date: 2019-04-04
 * Time: 11:02
 */
require_once "bundle.php";

class Video
{
    private $userid;
    private $vidurl;
    private $thumbnail;
    private $views;
    private $date;
    private $descreption;
    private $title;
    private $id;
    private $db_table = "labProj_videos";


    /**
     * @return mixed
     */
    public function getUserid()
    {
        return $this->userid;
    }

    /**
     * @param mixed $userid
     */
    public function setUserid($userid)
    {
        $this->userid = $userid;
    }

    /**
     * @return mixed
     */
    public function getVidurl()
    {
        return $this->vidurl;
    }

    /**
     * @param mixed $vidurl
     */
    public function setVidurl($vidurl)
    {
        $this->vidurl = $vidurl;
    }

    /**
     * @return mixed
     */
    public function getThumbile()
    {
        return $this->thumbile;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbile($thumbnail)
    {
        $this->thumbile = $thumbnail;
    }

    /**
     * @return mixed
     */
    public function getViews()
    {
        return $this->views;
    }

    /**
     * @param mixed $views
     */
    public function setViews($views)
    {
        $this->views = $views;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDescreption()
    {
        return $this->descreption;
    }

    /**
     * @param mixed $descreption
     */
    public function setDescreption($descreption)
    {
        $this->descreption = $descreption;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function addToDB()
    {
        $db = DB::getInstance();
        $query = "insert into $this->db_table (userid,vidurl,thumbnail,views, `date` ,descreption,title) values ('$this->userid','$this->vidurl','$this->thumbile','$this->views', '$this->date' ,'$this->descreption','$this->title')";
        $db->querySQL($query);

    }

    private function initWith($userid, $vidurl, $thumbnail, $views, $date, $descreption, $title, $id)
    {
        $this->userid  =$userid;
        $this->vidurl =$vidurl;
        $this->thumbile =$thumbnail;
        $this->views=$views;
        $this->date=$date;
        $this->descreption=$descreption;
        $this->title =$title;
        $this->id=$id;
    }

    function deleteVideo()
    {
        try {
            $db = DB::getInstance();
            $data = $db->querySql('Delete from $this->db_table where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id)
    {

        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT * FROM $this->db_table WHERE id = $id");
        $this->initWith($data->userid, $data->vidurl, $data->thumbnail, $data->views, $data->date, $data->descreption, $data->title, $data->id);
    }
    function getIdAfterAdd()
    {

        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT * FROM $this->db_table WHERE title = '$this->title' and vidurl = '$this->vidurl' and date = '$this->date' and userid = '$this->userid'");

        $this->setId($data->id);
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = "UPDATE $this->db_table set
			userid = ' $this->userid',
			vidurl = '$this->vidurl',
			thumbnail = ' $this->thumbile',
			views = ' $this->views',
			`date`  = '$this->date',
			descreption = '$this->descreption',
			title = ' $this->title'
				WHERE id = '$this->id'";

        $db->querySql($data);
    }

    function getAllVideos() {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table");
        return $data;
    }
    function getLatestVideos($limit = 10) {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table order by `date` desc limit $limit");
        return $data;
    }
    function getVideosByUser($user) {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table  where userid = '$user'");
        return $data;
    }
    function getVideosByCategory($category) {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table  where categoryid = '$category'");
        return $data;
    }
    function getTopVideos($limit = 10) {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table order by views desc limit $limit");
        return $data;
    }
    function check($id) {
        $this->initWithId($id);
        if ($this->id != null && $this->id == $id) {
            return true;
        }else{
        return false;
        }
    }
    function updateViews(){

      $this->views =  $this->views + 1;
        $this->updateDB();
    }

}