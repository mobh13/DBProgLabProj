<?php
/**
 * Created by PhpStorm.
 * User: madan
 * Date: 2019-04-04
 * Time: 11:47
 */

require_once "bundle.php";
class Likes
{

    private $id;
    private $userid;
    private $videoid;
    private $type;
    private $db_table = "labProj_Likes";
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
    public function getVideoid()
    {
        return $this->videoid;
    }

    /**
     * @param mixed $videoid
     */
    public function setVideoid($videoid)
    {
        $this->videoid = $videoid;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function addToDB()
    {
        $db = DB::getInstance();
        $query = "insert into $this->db_table (userid,videoid,`type`) values ('$this->userid','$this->videoid','$this->type')";
        $db->querySQL($query);
    }

    private function initWith($id, $videourl, $userid, $type)
    {
        $this->videoid  =$videourl;
        $this->userid =$userid;
        $this->type =$type;
        $this->id=$id;
    }

    function deleteLike()
    {
        try {
            $db = DB::getInstance();
            $data = $db->querySql('Delete from '. $this->db_table. ' where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id)
    {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT * FROM '. $this->db_table. ' WHERE id = ' . $id);
        $this->initWith($data->id, $data->videoid, $data->userid, $data->type);
    }
    function initWithVideoidAndUserid($videoid,$userid)
    {

        $db = DB::getInstance();

        $data = $db->singleFetch('SELECT * FROM '. $this->db_table .' WHERE videoid = ' . $videoid.' and userid = ' . $userid);
        $this->initWith($data->id, $data->videoid, $data->userid, $data->type);
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = "UPDATE $this->db_table set
			userid = ' $this->userid',
			videoid = '$this->videoid',
			`type`  = '$this->type'
				WHERE id = '$this->id'";

        $db->querySql($data);
    }
    public function getLikes($vidid){

        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT count(*) as noOFLikes FROM $this->db_table WHERE `type` = 1 and videoid = '$vidid'");
        return $data->noOFLikes;

    }
    public function getDisLikes($vidid){
        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT count(*) as noOFLikes FROM $this->db_table WHERE `type` = 2 and videoid = '$vidid'");
        return $data->noOFLikes;

    }
    public function exist($id){
        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT count(*) as noOFLikes , `type` FROM $this->db_table WHERE  videoid = '$id'");
        if ($data->noOfLikes == 0 ){
            return array(false,null);
        }else{

            return array(true,$data->type);
        }
    }
    public function existWithUserid($id,$userid){
        $db = DB::getInstance();
        $data = $db->singleFetch("SELECT count(*) as noOFLikes , `type` FROM $this->db_table WHERE  videoid = '$id' and userid = '$userid'");
        if ($data->noOFLikes == 0 ){
            return array(false,null);
        }else{

            return array(true,$data->type);
        }
    }


}