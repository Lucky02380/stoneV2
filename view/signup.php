<?php 
session_start();
// require_once("./config/db.php");
// require_once("./model/user.php");
// require_once("./controller/userController.php");

if (isset($_SESSION['username'])) {
    header('Location: /stoneV2/view/home.php');
}



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
</head>
<body>
    <h2>Sign Up</h2>

    <?php 
        if(isset($data['usernameFormat']))  echo $data['usernameFormat'];
        if(isset($data['passwordFormat']))  echo $data['passwordFormat'];
        if(isset($data['duplicateUser']))  echo $data['duplicateUser'];
        if(isset($data['signupStatus']))  echo $data['signupStatus'];

    ?>
    <form action="/stoneV2/controller/authController.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" name="signup">Sign Up</button>
    </form>
    
    <p>Already have an account? <a href="/stoneV2/view/login.php">Login</a></p>
    <!-- <form method="post">
        <p>Already have an account? <button type="submit" name="login">Login</button></p>
    </form> -->
        
    
</body>
</html>
