<?php


class Comments
{

    private $id;
    private $vidid;
    private $parentid;
    private $content;
    private $userid;
    private $date;
    private $db_table = "labProj_comments";

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
    public function getVidid()
    {
        return $this->vidid;
    }

    /**
     * @param mixed $vidid
     */
    public function setVidid($vidid)
    {
        $this->vidid = $vidid;
    }

    /**
     * @return mixed
     */
    public function getParentid()
    {
        return $this->parentid;
    }

    /**
     * @param mixed $parentid
     */
    public function setParentid($parentid)
    {
        $this->parentid = $parentid;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
     * @return string
     */
    public function getDbTable()
    {
        return $this->db_table;
    }

    /**
     * @param string $db_table
     */
    public function setDbTable($db_table)
    {
        $this->db_table = $db_table;
    }


    public function addToDB()
    {
        $db = DB::getInstance();
        $query = "insert into $this->db_table (vidid,parentid,`date`,content,userid) values ('$this->vidid','$this->parentid','$this->date','$this->content','$this->userid')";
        $db->querySQL($query);

    }

    private function initWith($id,
$vidid,
$parentid,
$content,
$userid,
$date)
    {
         $this->id = $id;
         $this->vidid = $vidid;
         $this->parentid = $parentid;
         $this->content = $content;
         $this->userid = $userid;
         $this->date = $date;
    }

    function deleteComment()
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
        $this->initWith($data->id, $data->vidid, $data->parentid, $data->content,$data->userid,$data->date);
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = "UPDATE $this->db_table set
			vidid = ' $this->vidid',
			parentid = '$this->parentid',
			userid = '$this->userid',
			content = '$this->content',
			`date`  = '$this->date'
				WHERE id = '$this->id'";

        $db->querySql($data);
    }
    function getVideoComments(){
    $db = DB::getInstance();
    $data = $db->multiFetch("Select * from $this->db_table where vidid = $this->vidid");
    return $data;

}
    function getCommentCount(){
        $db = DB::getInstance();
        $data = $db->singleFetch("Select count(*) as commentCount from $this->db_table where vidid = $this->vidid");
        return $data->commentCount;

    }
    function getSubComments(){
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table where parentid = $this->id");
        return $data;
    }
    function checkForSubComments(){
        $db = DB::getInstance();
        $data = $db->singleFetch("Select count(*) as counter from $this->db_table where parentid = $this->id");
 if ($data->counter > 0){

            return true;
        }else{

            return false;
        }
    }
}