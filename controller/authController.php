<?php

// if(session_status() !== PHP_SESSION_ACTIVE){
//     header('Location: /stoneV2/view/login.php');
// }
// else{
//     session_start(); 
//     if(isset($_SESSION['admin']) && $_SESSION['admin']===0){
//         header('Location: /stoneV2/view/home.php');
//     }
// }

//Alternate Solution
@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
}



require_once("../config/db.php");
require_once("../model/user.php");
require_once("../model/game.php");
// echo "trace1";


class authController{

    public $user;
    public $game;
    public function __construct($user, $game){
    
        $this->user = $user;
        $this->game = $game;

        
    }

    public function processLogin($username,$password){
        $passwordFormatType = 1;
        $usernameFormat = $this->isUsernameFormatValid($username);
        $passwordFormat = $passwordFormatType == 1 ? $this->ispasswordFormatValid1($password) : $this->ispasswordFormatValid2($password);
        if(!$usernameFormat){
            $data['usernameFormat'] = 'Username Format Invalid';
        }
        if(!$passwordFormat){
            $data['passwordFormat'] = 'Password Format Invalid';
        }
        if($usernameFormat && $passwordFormat){
            if(!$this->user->isUserAvailable($username)) $data['InvalidUsername'] = 'User not found, Invalid Username';
            else {
                if($this->user->login($username,$password)) {
                    @session_start();
                    $_SESSION['curScore'] = 0;
                    $_SESSION['username'] = $username;
                    $_SESSION['userid'] = $this->user->getUserInfo($username)['userid']; 
                    $_SESSION['admin'] = $this->user->isAdmin($_SESSION['userid']);
                    // echo var_dump($_SESSION);
                    // die();
                    
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
        // $row = $this->game->getTopPlays($_SESSION['userid']);
        


        //update all user's plays
        $val = $this->game->getAllPlays();
        while($row = $val->fetch_assoc()){
            $userid = $row['userid'];
            
            $res = [];
            for($i=1; $i<=5; $i++){
                array_push($res,(int)($row['score'.$i]));
            }
            array_push($res,$row['tempScore']);
            rsort($res);
            $res = array_slice($res, 0, 5);
            if($userid == $_SESSION['userid']){
                $_SESSION['minScore'] = $row['score5'];
                $_SESSION['plays'] = $res;
                $_SESSION['copyPlays'] = $res;
            }
            
            //update user plays considering last session score of user i.e tempScore
            $this->game->updatePlays($userid,$res);

            //reset tempScore
            $this->game->updateTempScore($userid,0);
        }



        

        //get the leaderboard
        $res = $this->game->getLeaderboard();
        $_SESSION['leaderBoard'] = $res;
    }

    //function to logout user
    public function processLogout(){
        // session_destroy();
        if(session_status() === PHP_SESSION_ACTIVE){
            // echo "trace1";
            // echo var_dump($_SESSION);
            // die;
            session_destroy();
            // echo "<script> location.href='/stoneV2/view/login.php'; </script>";

            header('Location: /stoneV2/view/login.php');
            // exit();
        }
        $data['status'] = 'Logged out successfully';
        // $this->loadView('../view/login.php',$data);
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
        if(strlen($password) < 8 || strlen($password) > 16) return 0; //password size not valid
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
    
    
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $auth = new authController(new User($Conn), new Game($Conn));
    if(isset($_POST["login"])){
        $auth->processLogin($username,$password);
    }
    if(isset($_POST["logout"])){
        $auth->processLogout();
    }
    
}




?>