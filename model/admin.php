<?php
session_start();
class Admin{
    private $Conn;
    // public $id;
    // public $username;

    public function __construct($Conn){
        $this->Conn = $Conn;
    }

    //Check if user already exist in db
    public function isAdminAvailable($username){
        $res = $this->Conn->query("select * from admins where username = '".$username."'");
        if($res->num_rows > 0) return 1;
        else return 0;
    }

    public function getAdminInfo($username){
        $res = $this->Conn->query("select * from admins where username = '".$username."'");
        if($res->num_rows > 0){
            $admin = $res->fetch_assoc();
            return $admin;
        }
        else return null;
    }

    //signup the user with username and password
    public function signupUser($username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $res = $this->Conn->query("insert into users (username,password) values('$username','$hashedPassword')");
        return $res;
    }

    //function to signup another admin
    public function signupAdmin($username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $res = $this->Conn->query("insert into admins (username,password) values('$username','$hashedPassword')");
        return $res;
    }
    
    //login the user and return success/failure
    public function login($username, $password){
        $res = $this->Conn->query("select * from admins where username = '".$username."'");
        if($res->num_rows > 0){
            $user = $res->fetch_assoc();
            if(password_verify($password, $user["password"])) return 1;
            else return 0;
        }
    }

    
}


?>