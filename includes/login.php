<?php
/**
 * Created by PhpStorm.
 * User: 201601310
 * Date: 27/03/2019
 * Time: 11:04 AM
 */
Class Login extends Users {

    public $ok;
    public $salt;
    public $domain;

    function __construct() {
        $this->ok = false;
        $this->salt = 'ENCRYPT';
        $this->domain = '';

        if (!$this->check_session())
            $this->check_cookie();

        return $this->ok;
    }

     function check_session() {

        if (!empty($_SESSION['id'])) {
            $this->ok = true;
            return true;
        }
        else
            return false;
    }

    function check_cookie() {
        if (!empty($_COOKIE['id'])) {
            $this->ok = true;
            return $this->check($_COOKIE['id']);
        }
        else
            return false;
    }

    function check($id) {
        $this->initWithId($id);
        if ($this->getId() != null && $this->getId() == $id) {
            $this->ok = true;

            $_SESSION['id'] = $this->getId();
            $_SESSION['username'] = $this->getUsername();
            setcookie('id', $_SESSION['id'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);

            return true;
        }
        else{
            $error[] = 'Wrong Username';


        return false;}
    }
    function checkUser($email, $password){
        $db = Database::getInstance();
        $data = $db->singleFetch('SELECT * FROM labProj_users WHERE email = \''.$email.'\' AND password = \''.$password.'\'');
        $this->initWith($data->id, $data->username, $data->password, $data->email);
    }
    function login($email, $password) {

        try {
            $this->checkUser($email, $password);

            if ($this->getId() != null) {
                $this->ok = true;

                $_SESSION['id'] = $this->getId();
                $_SESSION['username'] = $this->getUsername();
                setcookie('id', $_SESSION['id'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
                setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);

                return true;
            } else {
                $error[] = 'Wrong Username OR password';
            }
            return false;
        } catch (Exception $e) {
            $error[] = $e->getMessage();
        }

        return false;
    }

    function logout() {
        $this->ok = false;
        $_SESSION['id'] = '';
        $_SESSION['username'] = '';
        setcookie('id', '', time() - 3600, '/', $this->domain);
        setcookie('username', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }
    private function initWith($id, $username, $password, $email) {
        $this->setId($id) ;
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setEmail($email);
    }


}