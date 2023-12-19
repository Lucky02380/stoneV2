<?php

// echo var_dump($_SESSION);
// @session_start();

//Type1
// if(session_status() !== PHP_SESSION_ACTIVE){
//     header('Location: /stoneV2/view/login.php');
// }
// else{
//     //problem
//     if(isset($_SESSION['admin']) && $_SESSION['admin']===0){
//         header('Location: /stoneV2/view/home.php');
//     }
// }

@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
    header('Location: /stoneV2/view/login.php');
}
else{
    if(isset($_SESSION['admin']) && $_SESSION['admin']===0){
        header('Location: /stoneV2/view/home.php');
    }
}
require_once("../config/db.php");
require_once("../model/user.php");
require_once("../model/game.php");

class adminController{

    public $user, $game;
    public function __construct($user, $game){
        $this->user = $user;        
        $this->game = $game;
    }

    //function to signup a user
    public function processSignupUser($username, $password){
        
        //like this \\
        $passwordFormatType = 1;
        $usernameFormat = $this->isUsernameFormatValid($username);
        $passwordFormat = $passwordFormatType == 1 ? $this->ispasswordFormatValid1($password) : $this->ispasswordFormatValid2($password);
        if(!$usernameFormat){
            $data['UsernameFormat'] = 'Username Format Invalid';
        }
        if(!$passwordFormat){
            $data['PasswordFormat'] = 'Password Format Invalid';
        }
        if($usernameFormat && $passwordFormat){
            if($this->user->isUserAvailable($username)) $data['duplicateUser']  = 'Username Already Exist';
            else {
                if($this->user->signup($username,$password,0)){
                    $data['signupStatus'] = 'User Signed up Successfully';
                    $userid = $this->user->getUserInfo($username)['userid'];
                    $this->game->initaliseUserPlays($userid);
                }
                else $data['signupStatus'] = 'Unable to signup, Try Again Later';
            }
        }
        
        require_once '../view/adminHome.php';
        
    }


    //function to signup another admin
    public function signUpAdmin($username, $password){
        $passwordFormatType = 1;
        $usernameFormat = $this->isUsernameFormatValid($username);
        $passwordFormat = $passwordFormatType == 1 ? $this->ispasswordFormatValid1($password) : $this->ispasswordFormatValid2($password);
        if(!$usernameFormat){
            $data['UsernameFormat'] = 'Adminname Format Invalid';
        }
        if(!$passwordFormat){
            $data['PasswordFormat'] = 'AdminPass Format Invalid';
        }
        if($usernameFormat && $passwordFormat){
            if($this->user->isUserAvailable($username)) $data['duplicateUser']  = 'Username Already Exist, Choose Another';
            else {
                if($this->user->signup($username,$password,1)){
                    $data['signupStatus'] = 'Admin Signed up Successfully';
                }
                else $data['signupStatus'] = 'Unable to signup, Try Again Later';
            }
        }
        
        require_once '../view/adminHome.php';
    }


    //function to validate username format
    public function isUsernameFormatValid($username){
        if(strlen($username) < 3 || strlen($username) > 20) return 0; //username not valid
        for($i= 0;$i<strlen($username);$i++){
            $val = ord($username[$i]);
            if($val < 48 || ($val > 57 && $val < 65) || ($val > 90 && $val < 97) || ($val > 122)){
                return false; ////contains special Chars, username not valid
            }
        }
        return true;
    }
    //function to validate password format(size wise only) 
    public function ispasswordFormatValid1($password){
        if(strlen($password) <= 0 || strlen($password) > 10) return false; //password size not valid
        else return true; 
    }

    //function to validate password format(special char is required and a upper case  and a lower case and a number) 
    public function ispasswordFormatValid2($password){
        if(strlen($password) < 8 || strlen($password) > 16) return false; //password size not valid
        $isSpecialChar = false; $isUpperCase = false; $isLowerCase = false; $isNumber = false;
        for($i= 0;$i<strlen($password);$i++){
            $val = ord($password[$i]);
            if((($val >= 32 && $val <= 47) || ($val >= 58 && $val <= 64))) $isSpecialChar = true;
            else if(($val >= 48 && $val <= 57)) $isNumber = true;
            else if(($val >= 65 && $val <= 90)) $isUpperCase = true;
            else if(($val >= 97 && $val <= 122)) $isLowerCase = true;
        }
        return $isSpecialChar && $isNumber && $isUpperCase && $isLowerCase;
    }

};

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admin = new adminController(new User($Conn), new Game($Conn)); 
    if(isset($_POST['signup'])){
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        if($_POST['UserType'] === "user"){
            $admin->processSignupUser($username,$password);
        }
        else $admin->SignUpAdmin($username,$password);
    }
 

}





?>