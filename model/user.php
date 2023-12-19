<?php
// if(!isset($_SESSION)) session_start(); 
class User{
    private $table = "users";
    private $Conn;

    public function __construct($Conn){
        $this->Conn = $Conn;
    }

    //Check if user already exist in db
    public function isUserAvailable($username){
    
        try{
            $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
            if($res->num_rows > 0) return true;
            else return false;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }

    }

    //get user information
    public function getUserInfo($username){
        
        try{
            $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
            if($res->num_rows > 0){
                $user = $res->fetch_assoc();
                return $user;
            }
            else return null;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }

    }

    //signup the user with username and password
    public function signup($username, $password,$val){
        
        try{
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $res = $this->Conn->query("insert into ".$this->table." (username,password,admin) values('$username','$hashedPassword',$val)");
            return $res;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }
    
    //login the user and return success/failure
    public function login($username, $password){
        
        try{
            $res = $this->Conn->query("select * from ".$this->table." where username = '".$username."'");
            if($res->num_rows > 0){
                $user = $res->fetch_assoc();
                if(password_verify($password, $user["password"])) return true;
                else return false;
            }
            return false;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    public function isAdmin($userid){
        
        try{
            $res = $this->Conn->query("select * from ".$this->table." where userid = ".$userid."");
            if($res->num_rows > 0){
                $user = $res->fetch_assoc();
                return (int)$user["admin"];
            }
            return false;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    
}


?>