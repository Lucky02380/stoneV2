<?php

session_start();

require_once("../config/db.php");
require_once("../model/user.php");
require_once("../model/game.php");
require_once("../model/admin.php");
require_once("../controller/authController.php");

class adminController{

    public $user, $game, $admin;
    public function __construct($user, $game, $admin){
        $this->user = $user;
        $this->game = $game;
        $this->admin = $admin;
    }

    public function processLogin($username,$password){
        if($this->admin->isAdminAvailable($username) === 0) $data['InvalidAdmin'] = 'Admin not found, Invalid Admin';
        else {
            if($this->admin->login($username,$password) == 1) {
                $_SESSION['admin'] = true;
                $_SESSION['username'] = $username;
                $data['loginStatus'] = 'You Logged in Successfully';

            }
            else {
                $data['loginStatus'] = 'Unable to Login, Incorrect Password';
            }
        }

        require_once '../view/adminLogin.php';
    }

    public function processSignupUser($username, $password){
        
        //like this \\
        $usernameFormat = $this->isUsernameFormatValid($username);
        $passwordFormat = $this->ispasswordFormatValid($password);
        if($usernameFormat === 0){
            $data['usernameFormat'] = 'Username Format Invalid';
        }
        if($passwordFormat === 0){
            $data['passwordFormat'] = 'Password Format Invalid';
        }
        if($usernameFormat === 1 && $passwordFormat === 1){
            if($this->user->isUserAvailable($username) === 1) $data['duplicateUser']  = 'Username Already Exist';
            else {
                if($this->user->signup($username,$password) == 1 ){
                    $data['signupStatus'] = 'User Signed up Successfully';
                    $userid = $this->user->getUserInfo($username)['userid'];
                    $this->game->initaliseUserPlays($userid);
                }
                else $data['signupStatus'] = 'Unable to signup, Try Again Later';
            }
        }
        
        require_once '../view/adminHome.php';
        
    }

    public function isUsernameFormatValid($username){
        if(strlen($username) <= 2 || strlen($username) > 20) return 0; //username not valid
        for($i= 0;$i<strlen($username);$i++){
            $val = ord($username[$i]);
            if($val < 48 || ($val > 57 && $val < 65) || ($val > 90 && $val < 97) || ($val > 122)){
                return 0; ////contains special Chars, username not valid
            }
        }
        return 1;
    }

    public function ispasswordFormatValid($password){
        if(strlen($password) <= 0 || strlen($password) > 10) return 0; //password size not valid
        else return 1; 
    }

    public function signUpAdmin($username, $password){
        $usernameFormat = $this->isUsernameFormatValid($username);
        $passwordFormat = $this->ispasswordFormatValid($password);
        if($usernameFormat === 0){
            $data['AdminnameFormat'] = 'Adminname Format Invalid';
        }
        if($passwordFormat === 0){
            $data['AdminPassFormat'] = 'AdminPass Format Invalid';
        }
        if($usernameFormat === 1 && $passwordFormat === 1){
            if($this->admin->isAdminAvailable($username) === 1) $data['duplicateAdmin']  = 'Adminname Already Exist';
            else {
                if($this->admin->signUpAdmin($username,$password) == 1 ){
                    $data['signupStatusAdmin'] = 'Admin Signed up Successfully';
                }
                else $data['signupStatusAdmin'] = 'Unable to signup, Try Again Later';
            }
        }
        
        require_once '../view/adminHome.php';
    }


};

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $admin = new adminController(new User($Conn), new Game($Conn), new Admin($Conn)); 

    if(isset($_POST['login'])){
        $username = isset($_POST['AdminUsername']) ? $_POST['AdminUsername'] : '';
        $password = isset($_POST['AdminPassword']) ? $_POST['AdminPassword'] : '';
        $admin->processLogin($username,$password);
    }
    if(isset($_POST['signUpuser'])){
        $username = isset($_POST['signUpusername']) ? $_POST['signUpusername'] : '';
        $password = isset($_POST['signUppassword']) ? $_POST['signUppassword'] : '';
        $admin->processSignupUser($username,$password);
    }
    if(isset($_POST['signUpAdmin'])){
        $username = isset($_POST['signUpAdminUsername']) ? $_POST['signUpAdminUsername'] : '';
        $password = isset($_POST['signUpAdminPassword']) ? $_POST['signUpAdminPassword'] : '';
        $admin->SignUpAdmin($username,$password);
    }

}





?>