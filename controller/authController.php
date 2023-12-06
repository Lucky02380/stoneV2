<?php
session_start();
require_once("../config/db.php");
require_once("../model/user.php");
require_once("../model/game.php");
// echo "trace1";


class authController{

    public $user;
    public $game;
    public $username;
    public $password;
    public $usernameFormat = false;
    public $passwordFormat = false;
    public function __construct($user, $game, $username, $password){
    
        $this->user = $user;
        $this->game = $game;
        //NOTE: can be further improved with code readability, Hint: this should be here, move this to functions
        $this->username = $this->isUsernameFormatValid($username) === 1 ? $username :"";
        $this->password = $this->ispasswordFormatValid($password) === 1 ? $password :"";
        $this->usernameFormat = strlen($this->username) == 0 ? 0 : 1;
        $this->passwordFormat = strlen($this->password) == 0 ? 0 : 1;

        
    }

    public function processLogin(){
        if($this->usernameFormat === 0){
            $data['usernameFormat'] = 'Username Format Invalid';
        }
        if($this->passwordFormat === 0){
            $data['passwordFormat'] = 'Password Format Invalid';
        }
        if($this->usernameFormat === 1 && $this->passwordFormat === 1){
            if($this->user->isUserAvailable($this->username) === 0) $data['InvalidUsername'] = 'User not found, Invalid Username';
            else {
                if($this->user->login($this->username,$this->password) == 1) {
                    
                    $_SESSION['curScore'] = 0;
                    $_SESSION['username'] = $this->username;
                    $_SESSION['userid'] = $this->user->getUserInfo($this->username)['userid']; 
                    $data['loginStatus'] = 'You Logged in Successfully';
                    $this->setPlaysAndLeaderboard();

                }
                else {
                    $data['loginStatus'] = 'Unable to Login, Incorrect Password';
                }
            }
        }
        
        
        $this->loadView('../view/login.php', $data);
    }

    //get the user's top five plays and leaderboard
    public function setPlaysAndLeaderboard(){
        $row = $this->game->getTopPlays($_SESSION['userid']);
        $res = [];
        for($i=1; $i<=5; $i++){
            array_push($res,(int)($row['score'.$i]));
        }
        array_push($res,$row['tempScore']);
        rsort($res);
        $res = array_slice($res, 0, 5);
        $_SESSION['plays'] = $res;
        $this->game->updatePlays($_SESSION['userid'],$res);

        //get the leaderboard
        $res = $this->game->getLeaderboard();
        $_SESSION['leaderBoard'] = $res;
    }

    //function to logout user
    public function processLogout(){
        session_destroy();
        $data['status'] = 'Logged out successfully';
        $this->loadView('../view/login.php',$data);
    }

    // Load view and pass data
    public function loadView($viewFile, $data){
        require_once $viewFile;
    }

   
    //function to validate username format
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

    //function to validate password format   
    public function ispasswordFormatValid($password){
        if(strlen($password) <= 0 || strlen($password) > 10) return 0; //password size not valid
        else return 1; 
    }
    
    
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $auth = new authController(new User($Conn), new Game($Conn), $username, $password);
    if(isset($_POST["login"])){
        $auth->processLogin();
    }
    if(isset($_POST["logout"])){
        $auth->processLogout();
    }
    
}


?>