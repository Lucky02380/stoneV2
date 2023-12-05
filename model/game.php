<?php
session_start();


class Game{
    private $table = "games";
    private $Conn;
    // public $id;
    // public $username;

    public function __construct($Conn){
        $this->Conn = $Conn;
    }

    //create user with score 0, can also be done using default while altering db
    public function createUserInitialScore($userid,$username){
        $val = $this->Conn->query("insert into ".$this->table." (userid,score,username) values('".$userid."','0','".$username."')");
        return $val;
    }

    //signup the user with username and password
    public function updateUserScore($userid,$score){
        $res = $this->Conn->query("update ".$this->table." set score = ".$score." where userid = ".$userid."");
        return $res;
    }
    
    public function getGameInfo($userid){
        $res = $this->Conn->query("select * from ".$this->table." where userid = '".$userid."'");
        if($res->num_rows > 0){
            $game = $res->fetch_assoc();
            return $game;
        }
        else return null;
    }

    public function updatePlays($userid,$plays){
        $res = true;
        for($i=0; $i<count($plays); $i++){
            $res &= $this->updatePlay($userid,$plays[$i],$i+1);
        }
        return $res;
    }
    
    public function updatePlay($userid,$play,$i){
        $val = $this->Conn->query("update plays set score".$i. " = ".$play." where userid = ".$userid."");
        return $val;
    }

    // public function updatePlayss($userid, $i){

    // }

    public function getTopPlays($userid){
        $res = $this->Conn->query("select * from plays where userid = '".$userid."'");
        if($res->num_rows > 0){
            $row = $res->fetch_assoc();
            return $row;
        }
        else return null;
    }

    public function updateTempScore($userid,$score){
        $val = $this->Conn->query("update plays set tempScore  = ".$score." where userid = ".$userid."");
        return $val;
    }

    public function initaliseUserPlays($userid){
        $val = $this->Conn->query("insert into plays (userid,score1,score2,score3,score4,score5,tempScore) values('".$userid."','0','0','0','0','0','0')");
        return $val;
    }

    public function getLeaderboard(){
        $val = $this->Conn->query("select username,score from ".$this->table." where updated_at >= date_sub(curdate(),interval 30 day) order by score desc limit 3");
        if($val->num_rows > 0){
            $players = [];
            while($row = $val->fetch_assoc()){
                $players[$row['username']] = $row['score'];
            }
            // var_dump($val);
            return $players;
        }
        return null;
    }

}


?>