<?php 
session_start();

session_start();
if (isset($_SESSION['admin'])) {
    header('Location: /stoneV2/view/adminHome.php');
}
require_once("../config/db.php");
require_once("../model/game.php");


class gameController{
    
    public $game;
    public $username;
    public $userid;
    public $curScore;
    public $maxScore;

    public function __construct($game){
        $this->game = $game;
        $this->username = $_SESSION['username'];
        $this->curScore = $_SESSION['curScore'];
        $this->userid = $_SESSION['userid'];
        $this->findMaxScore();
        
    }

    //function to find max score of a user
    public function findMaxScore() {
        $gameInfo = $this->game->getGameInfo($this->userid);
        if($gameInfo){
            $this->maxScore = $gameInfo['score'];
        }
        else $this->game->createUserInitialScore($this->userid,$this->username);  //if user haven't played any game yet, initalise user with score 0
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
        $this->game->updateTempScore($this->userid,$this->curScore);

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
            //NOTE: Can be further optimised in terms of space
            if($userChoice === $choices[0]){
                $newChoices = ['scissors','scissors','scissors','paper','paper','paper','paper','paper','paper','paper'];
                return $newChoices[array_rand($newChoices)];
            }
            else if($userChoice === $choices[1]){
                $newChoices = ['rock','rock','rock','scissors','scissors','scissors','scissors','scissors','scissors','scissors'];
                return $newChoices[array_rand($newChoices)];
            }
            else{
                $newChoices = ['paper','paper','paper','rock','rock','rock','rock','rock','rock','rock'];
                return $newChoices[array_rand($newChoices)];
            }
        }
    }

    //function to update user score
    public function updateUserScore(){
        if($this->curScore > $this->maxScore){
            $this->game->updateUserScore($this->userid,$this->curScore);
        }
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