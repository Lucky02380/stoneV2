<?php 

//Type1
// @session_start(); 
// if(session_status() !== PHP_SESSION_ACTIVE){
//     header('Location: /stoneV2/view/login.php');
// }
// else{
//     session_start(); 
//     if(isset($_SESSION['admin']) && $_SESSION['admin']===1){
//         header('Location: /stoneV2/view/adminHome.php');
//     }
// }


//Alternate Solution
@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
    header('Location: /stoneV2/view/login.php');
}
else if(isset($_SESSION['admin'])){
    if($_SESSION['admin']===1){
        header('Location: /stoneV2/view/adminHome.php');
    }
}

require_once("../config/db.php");
require_once("../model/game.php");


class gameController{
    
    public $game;
    public $username;
    public $userid;
    public $curScore;
    public $minScore;

    public function __construct($game){
        $this->game = $game;
        $this->username = $_SESSION['username'];
        $this->curScore = $_SESSION['curScore'];
        $this->userid = $_SESSION['userid'];
        $this->findMinScore();
        
    }

    //function to find max score of a user
    public function findMinScore() {
        $gameInfo = $this->game->getGameInfo($this->userid);
        if($gameInfo){
            $this->minScore = $gameInfo['score'];
        }
        else $this->game->createUserInitialScore($this->userid);  //if user haven't played any game yet, initalise user with score 0
    }

    //function to generate winner of current game
    public function generateWinner($userChoice, $mode){

        $computerChoice = $this->gameMode($userChoice,$mode);
        $data['computerChoice'] = $computerChoice;
        $score=0;
        if($userChoice == $computerChoice){
            $data['status'] = 0;
        }
        else if (($userChoice == 'rock' && $computerChoice == 'scissors') || ($userChoice == 'paper' && $computerChoice == 'rock') || ($userChoice == 'scissors' && $computerChoice == 'paper')) {
            $data['status'] = 1;
            $score++;
        } 
        else {
            $data['status'] = -1;
        }
        $this->curScore += $score;
        $_SESSION['curScore'] = $this->curScore;

        $this->updateUserScore();
        
        //update user current session score
        if(isset($_SESSION['minScore']) && $_SESSION['minScore'] < $this->curScore){
            $this->game->updateTempScore($this->userid,$this->curScore);
        }
        
        
        //top plays update locally
        $this->updateUserPlaysLocally();



        $players = $this->makeLeaderboard();

        //custom sort for players
        function sortPlayers($a, $b){
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }

        if(array_key_exists($this->username, $players)){
            $players[$this->username] = max($players[$this->username],(int)$_SESSION['curScore']);
        }
        else $players[$this->username] = (int)$_SESSION['curScore'];
        
        uasort($players,'sortPlayers');
        $players = array_slice($players,0,3);
        $_SESSION['leaderBoard'] = $players;
        require_once '../view/home.php';
    }

    public function gameMode($userChoice,$mode){
        $choices = ['rock', 'paper', 'scissors'];
        if($mode === "Easy"){
            return $choices[array_rand($choices)];
        }
        else{ //hard mode win:lose = 30:70
            $win = 10; //win = 1-100%
            $newChoice = rand(1,100);
            if($userChoice === $choices[0]){
                if($newChoice <= $win) return 'scissors';
                return 'paper'; 
            }
            else if($userChoice === $choices[1]){
                if($newChoice <= $win) return 'rock';
                return 'scissors';
            }
            else{
                if($newChoice <= $win) return 'paper';
                return 'rock';
            }
        }
    }

    //function to update user score
    public function updateUserScore(){
        if($this->curScore > $this->minScore){
            $this->game->updateUserScore($this->userid,$this->curScore);
        }
    }


    //function to update user plays locally
    public function updateUserPlaysLocally(){
        $copyPlays = $_SESSION['plays'];
        array_push($copyPlays,(int)$_SESSION['curScore']);
        $copyPlays = array_unique($copyPlays);
        rsort($copyPlays);
        $index = 5;
        for ($i = 0; $i < count($copyPlays); $i++) {
            if ($copyPlays[$i] == 0) {
                $index = $i;
                break;
            }
        }
        $copyPlays = array_slice($copyPlays, 0, min(5,$index));
        $_SESSION['copyPlays'] = $copyPlays;
    }

    //function to get leaderboard
    public function makeLeaderboard(){
        $res = $this->game->getLeaderboard();
        return $res;
    }



};



if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $game = new gameController(new Game($Conn));
    $userChoice = $_POST['user_choice'];
    $mode = $_POST['mode'];
    if(isset($_POST['play'])){
        $game->generateWinner($userChoice,$mode);
    }
}  




?>