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
    private $dob;
    private $avatar;
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
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
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

    function deleteUser() {
        try {
            $db = DB::getInstance();
            $data = $db->querySql('Delete from labProj_users where id=' . $this->id);
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }

    function initWithId($id) {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT * FROM labProj_users WHERE id = ' . $id);
        $this->init($data->id, $data->username, $data->password, $data->email,$data->avatar,$data->dob);
    }
    function initWithUsername($username) {

        $db = DB::getInstance();
        $data = $db->singleFetch('SELECT * FROM labProj_users WHERE username = ' . $username);
        $this->init($data->id, $data->username, $data->password, $data->email,$data->avatar,$data->dob);
    }
    private function init($id, $username, $password, $email,$avatar,$dob) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->dob = $dob;
    }
    function getAllUsers() {
        $db = DB::getInstance();
        $data = $db->multiFetch('Select * from labProj_users');
        return $data;
    }
    function updateDB() {

        $db = DB::getInstance();
        $data = 'UPDATE labProj_users set
			email = \'' . $this->email . '\' ,
			username = \'' . $this->username . '\' ,
			password = \'' . $this->password . '\'  ,
			dob = \'' . $this->dob . '\',  
			avatar = \'' . $this->avatar . '\'  
				WHERE id = ' . $this->id;
        $db->querySql($data);
    }
    function registerUser() {

        try {
            $db = DB::getInstance();
            $data = $db->querySql('INSERT INTO labProj_users (id, username, password, email,avatar,dob) VALUES (NULL, \'' . $this->username . '\',\'' . $this->password . '\',\'' . $this->email . '\',\'' . $this->avatar . '\',\'' . $this->dob . '\')');
            return true;
        } catch (Exception $e) {
            echo 'Exception: ' . $e;
            return false;
        }
    }





    function checkExist(){

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