<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 03/04/2019
 * Time: 10:55 AM
 */

class Category{

    private $id;
    private $title;
    private $db_table = "labProj_category";

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
    public function addToDB()
    {
        $db = DB::getInstance();
        $query = "insert into $this->db_table (title) values ('$this->title')";
        $db->querySQL($query);
    }

    private function initWith($title, $id)
    {

        $this->title =$title;
        $this->id=$id;
    }

    function deleteCategory()
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
        $this->initWith($data->title, $data->id);
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = "UPDATE $this->db_table set
			title = ' $this->title'
			
				WHERE id = '$this->id'";
        $db->querySql($data);
    }
    function getAllCategories() {
        $db = DB::getInstance();
        $data = $db->multiFetch("Select * from $this->db_table");
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


}