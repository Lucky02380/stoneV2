<?php
session_start();
class User{
    private $table = "users";
    private $Conn;
    // public $id;
    // public $username;

    public function __construct($Conn){
        $this->Conn = $Conn;
    }

    //Check if user already exist in db
    public function isUserAvailable($username){
        $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
        if($res->num_rows > 0) return 1;
        else return 0;
    }

    public function getUserInfo($username){
        $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
        if($res->num_rows > 0){
            $user = $res->fetch_assoc();
            return $user;
        }
        else return null;
    }

    //signup the user with username and password
    public function signup($username, $password){
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $res = $this->Conn->query("insert into ".$this->table." (username,password) values('$username','$hashedPassword')");
        return $res;
    }
    
    //login the user and return success/failure
    public function login($username, $password){
        $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
        if($res->num_rows > 0){
            $user = $res->fetch_assoc();
            if(password_verify($password, $user["password"])) return 1;
            else return 0;
        }
    }

    
}


?>