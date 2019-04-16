<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 27/03/2019
 * Time: 10:15 AM
 */

class Files
{
    private $id;
    private $vidid;
    private $fileurl;
    private $db_table = "labProj_files";

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
    public function getFileurl()
    {
        return $this->fileurl;
    }

    /**
     * @param mixed $fileurl
     */
    public function setFileurl($fileurl)
    {
        $this->fileurl = $fileurl;
    }
    public function addToDB()
    {
        $db = DB::getInstance();
        $query = "insert into $this->db_table (vidid,fileurl) values ('$this->vidid','$this->fileurl')";
        $db->querySQL($query);
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = 'UPDATE '.$this->db_table.' set
			vidid = \'' . $this->vidid . '\' ,
			fileurl = \'' . $this->fileurl . '\' ,
				WHERE id = ' . $this->id;
        $db->querySql($data);
    }

    function getAllFiles() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from '.$this->db_table);
        return $data;
    }

    function getVideoFiles() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from '.$this->db_table.' where vidid=' . $this->vidid);

        return $data;
    }
    function checkForFiles() {
        $db = DB::getInstance();
        $data = $db->singleFetch('Select count(*) as fileCount from '.$this->db_table.' where vidid=' . $this->vidid);
        return $data->fileCount;
    }
}