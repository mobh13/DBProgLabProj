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
        parent::__construct();
        $this->ok = false;
        $this->salt = 'ENCRYPT';
        $this->domain = '';

        if (!$this->check_session())
            $this->check_cookie();

        return $this->ok;
    }

    function check_session() {

        if (!empty($_SESSION['uid'])) {
            $this->ok = true;
            return true;
        }
        else
            return false;
    }

    function check_cookie() {
        if (!empty($_COOKIE['uid'])) {
            $this->ok = true;
            return $this->check($_COOKIE['uid']);
        }
        else
            return false;
    }

    function check($uid) {
        $this->initWithUid($uid);
        if ($this->getUid() != null && $this->getUid() == $uid) {
            $this->ok = true;

            $_SESSION['uid'] = $this->getUid();
            $_SESSION['username'] = $this->getUsername();
            setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
            setcookie('username', $_SESSION['username'], time() + 60 * 60 * 24 * 7, '/', $this->domain);

            return true;
        }
        else
            $error[] = 'Wrong Username';


        return false;
    }

    function login($username, $password) {

        try {
            $this->checkUser($username, $password);

            if ($this->getUid() != null) {
                $this->ok = true;

                $_SESSION['uid'] = $this->getUid();
                $_SESSION['username'] = $this->getUsername();
                setcookie('uid', $_SESSION['uid'], time() + 60 * 60 * 24 * 7, '/', $this->domain);
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
        $_SESSION['uid'] = '';
        $_SESSION['username'] = '';
        setcookie('uid', '', time() - 3600, '/', $this->domain);
        setcookie('username', '', time() - 3600, '/', $this->domain);
        session_destroy();
    }

}