<?php
// if(!isset($_SESSION)) session_start(); 


class Game{
    private $table = "games";
    private $Conn;
    // public $id;
    // public $username;

    public function __construct($Conn){
        $this->Conn = $Conn;
    }

    //create user with score 0
    public function createUserInitialScore($userid){
        
        try{
            $val = $this->Conn->query("insert into ".$this->table." (userid,score) values('".$userid."','0')");
            return $val;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    //signup the user with username and password
    public function updateUserScore($userid,$score){
        

        try{
            $res = $this->Conn->query("update ".$this->table." set score = ".$score." where userid = ".$userid."");
            return $res;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }
    
    //get user game information
    public function getGameInfo($userid){

        try{
            $res = $this->Conn->query("select * from ".$this->table." where userid = '".$userid."'");
            if($res->num_rows > 0){
                $game = $res->fetch_assoc();
                return $game;
            }
            else return null;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    //function user plays
    public function updatePlays($userid,$plays){
       
        try{
            $res = true;
            for($i=0; $i<count($plays); $i++){
                $res &= $this->updatePlay($userid,$plays[$i],$i+1);
            }
            return $res;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }
    
    public function updatePlay($userid,$play,$i){

        try{
            $val = $this->Conn->query("update plays set score".$i. " = ".$play." where userid = ".$userid."");
            return $val;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    public function getAllPlays(){
        try{
            $res = $this->Conn->query("select * from plays");
            return $res;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }

    }

    // function to get user top plays
    public function getTopPlays($userid){
        
        try{
            $res = $this->Conn->query("select * from plays where userid = '".$userid."'");
            if($res->num_rows > 0){
                $row = $res->fetch_assoc();
                return $row;
            }
            else return null;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    // update user session score
    public function updateTempScore($userid,$score){

        try{
            $val = $this->Conn->query("update plays set tempScore  = ".$score." where userid = ".$userid."");
            return $val;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    //iniatilise user plays
    public function initaliseUserPlays($userid){
        
        try{
            $val = $this->Conn->query("insert into plays (userid,score1,score2,score3,score4,score5,tempScore) values('".$userid."','0','0','0','0','0','0')");
            return $val; 
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    //get leaderboard
    public function getLeaderboard(){

        try{
            $t = 3; $day = 30;
            //$val = $this->Conn->query("select username,score from ( select username,score from ".$this->table." where updated_at >= date_sub(curdate(),interval 30 day) ) as freshScores order by score desc limit ".$t."");
            $val = $this->Conn->query("select newgames.score, users.username from (select userid,score from (select userid,score from ".$this->table." where updated_at >= date_sub(curdate(),interval ".$day." day)) as freshScores order by score desc limit ".$t.") as newgames inner join users on newgames.userid = users.userid");
            if($val->num_rows > 0){
                $players = [];
                while($row = $val->fetch_assoc()){
                    $players[$row['username']] = $row['score'];
                }
                return $players;
            }
            return null;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

    //get minimum play score of a user
    public function getMinScore($userid){
       
        try{
            $res = $this->Conn->query("select * from plays where userid = '".$userid."'");
            return $res;
        }catch(Exception $e){
            echo "Caught general exception: " . $e->getMessage();
        }
    }

}


?>