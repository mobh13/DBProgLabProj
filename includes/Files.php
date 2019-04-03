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
    function updateDB() {

        $db = Database::getInstance();
        $data = 'UPDATE files set
			fname = \'' . $this->fname . '\' ,
			ftype = \'' . $this->ftype . '\' ,
			flocation = \'' . $this->flocation . '\' ,
                        uid = ' . $this->uid . '
				WHERE fid = ' . $this->fid;
        $db->querySql($data);
    }

    function getAllFiles() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from files');
        return $data;
    }

    function getVideoFiles() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from files where vidid=' . $this->vidid);
        return $data;
    }
}