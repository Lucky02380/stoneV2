<?php 
session_start();
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

    public function findMaxScore() {
        $gameInfo = $this->game->getGameInfo($this->userid);
        if($gameInfo){
            $this->maxScore = $gameInfo['score'];
        }
        else $this->game->createUserInitialScore($this->userid,$this->username);
    }

    public function generateWinner($userChoice, $mode){
        
        // $choices = ['rock', 'paper', 'scissors'];
        // $computerChoice = $choices[array_rand($choices)];
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
        // echo "trace1";
        // $plays = $this->updateUserPlays($this->curScore);
        $this->game->updateTempScore($this->userid,$this->curScore);
        $players = $this->makeLeaderboard();

        //custom sort of players
        function sortPlayers($a, $b){
            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? 1 : -1;
        }
        $players[$this->username] = (int)$_SESSION['curScore'];
        uasort($players,'sortPlayers');
        $players = array_slice($players,0,2);

        require_once '../view/home.php';
    }

    public function gameMode($userChoice,$mode){
        $choices = ['rock', 'paper', 'scissors'];
        if($mode === "Easy"){
            return $choices[array_rand($choices)];
        }
        else{ //hard mode win:lose = 30:70
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

    public function updateUserScore(){
        if($this->curScore > $this->maxScore){
            $this->game->updateUserScore($this->userid,$this->curScore);
        }
    }

    public function updateUserPlays($score){
        

        $res = $this->game->getTopPlays($this->userid);

        for($i=1; $i<=5; $i++){
            $plays["score".$i] = (int)($res['score'.$i]);
        }
        
        // if($score > $plays["score1"]){
        //     $plays[score]
        // }
        for($i=1; $i<=5; $i++){
            if($score > $plays["score".$i]){
                //update in dp
                break;
            }
        }

    }

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