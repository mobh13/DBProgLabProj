<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 27/03/2019
 * Time: 10:15 AM
 */

class Users
{

    private $id;
    private $username;
    private $password;
    private $email;
    private $name;
    private $avatar;
    protected $db_table="labProj_users";
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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $dob
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    private function initWith($id, $username, $password, $email,$avatar,$name) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->name = $name;
    }

    function deleteUser() {
        try {
            $db = DB::getInstance();
            $data = $db->querySql('Delete from '.$this->db_table.' where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT * FROM '.$this->db_table.' WHERE id = ' . $id);
        $this->initWith($data->id, $data->username, $data->password, $data->email,$data->avatar,$data->name);
    }

    function checkUser($email, $password){
        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT * FROM '.$this->db_table.' WHERE email = \''.$email.'\' AND password = \''.$password.'\'');
        $this->initWith($data->id, $data->username, $data->password, $data->email,$data->avatar,$data->name);
    }

    function checkWithUsername() {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT count(*) as counter FROM '.$this->db_table.' WHERE username = \'' . $this->username.'\'');
        if ($data-> counter == 0) {
            return false;
        }else{
            return true;
        }
    }
    function checkWithEmail() {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT count(*) as counter FROM '.$this->db_table.' WHERE email = \'' . $this->email.'\'');
        if ($data-> counter == 0) {
            return false;
        }else{
            return true;
        }

    }

    function registerUser() {

        try {
            $db = DB::getInstance();
            $data = $db->querySql('INSERT INTO '.$this->db_table.' (id, username, password, email,`name`,avatar) VALUES (NULL, \'' . $this->username . '\',\'' . $this->password . '\',\'' . $this->email . '\',\'' . $this->name . '\',\'' . $this->avatar . '\')');
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }


    function updateDB() {

        $db = DB::getInstance();
        $data = 'UPDATE '.$this->db_table.' set
			email = \'' . $this->email . '\' ,
			name = \'' . $this->name . '\' ,
			username = \'' . $this->username . '\' ,
			avatar = \'' . $this->avatar . '\' ,
			password = \'' . $this->password . '\'  
				WHERE id = ' . $this->id;
        $db->querySql($data);
    }

    function getAllusers() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from '.$this->db_table);
        return $data;
    }




    public function isValid() {
        $errors = array();

        if (empty($this->username))
            $errors[] = 'You must enter first name';
        else {
            if (!$this->initWithUsername())
                $errors[] = 'This Username address is already registered';
        }

        if (empty($this->email))
            $errors[] = 'You must enter email';


        if (empty($this->password))
            $errors[] = 'You must enter password';

        return $errors;
    }



}