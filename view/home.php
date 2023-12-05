<?php
session_start();
// echo var_dump($_SESSION);
// echo $_SESSION['username'];
if(!isset($_SESSION['username'])){
    header('Location: /stoneV2/view/signup.php');
}
$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
$plays = $_SESSION['plays'];
$copyPlays = $plays;
// var_dump($copyPlays);
array_push($copyPlays,(int)$_SESSION['curScore']);
rsort($copyPlays);
$copyPlays = array_slice($copyPlays, 0, 5);

$players = $_SESSION['leaderBoard'];

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rock Paper Scissors</title>
</head>
<body>

    <h2 >Welcome, <?php echo $username; ?>!</h2>

    <!-- Rock Paper Scissors game form -->
    <form action="/stoneV2/controller/gameController.php" method="post">
        <label for="user_choice">Choose: </label>
        <select name="user_choice" id="user_choice">
            <option value="rock">Rock</option>
            <option value="paper">Paper</option>
            <option value="scissors">Scissors</option>
        </select>
        <select name="mode" id="mode">
            <option value="Easy">Easy</option>
            <option value="Hard">Hard</option>
        </select>
        <button type="submit" name="play">Play</button>
    </form>

    <?php
    if(isset($data['status'])){
        switch ($data['status']) {
            case -1:
                echo "<h2 style='color: red '>You Lose</h2>";
                break;
            case 1:
                echo "<h2 style='color: green '>You Won</h2>";
                break;
            case 0:
                echo "<h2 style='color: blue '>It's a Tie</h2>";
                break;
            default:
                echo "Result Not Found";
        }
    }
    else echo "<h2 style='color: black '>Play Game</h2>";

    if(isset($data['computerChoice'])) echo "<h3 style='color: black '>Computer Chooses: ".$data['computerChoice']." </h3>";


    echo "<h3>Your Score: ".(isset($_SESSION['curScore'])?$_SESSION['curScore']:0). " </h3>";
    ?>

    

    <h3>Your Top 5 Plays:</h3>
    <?php 
        $i=1;
        foreach ($copyPlays as $play){
            echo "<ul><li> score$i = ".$play."</li></ul>";
            $i++;
        }
    ?> 



    <h3>Leaderboard:</h3>
    <?php 
        $i=1;
        // var_dump($players);
        foreach ($players as $player => $score){
            echo "<ol>Rank ".$i."). $player with score ".$score."</ol>";
            $i++;
        }
    ?>

    

    <!-- LogOut -->
    <form action="/stoneV2/controller/authController.php" method="POST">
        <button type="submit" name="logout">LogOut</button>
    </form>

    
</body>
</html>