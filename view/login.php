<?php
session_start();
// echo var_dump($_SESSION);
// $error = '';
if (isset($_SESSION['username'])) {
    header('Location: /stoneV2/view/home.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php 
        if(isset($data['usernameFormat']))  echo $data['usernameFormat'];
        if(isset($data['passwordFormat']))  echo $data['passwordFormat'];
        if (isset($data['InvalidUsername'])) echo $data['InvalidUsername']; 
        if (isset($data['loginStatus'])) echo $data['loginStatus'];
        // echo "trace1";
    ?>
    <form action="/stoneV2/controller/authController.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" name="login">Login</button>
    </form>
    
    <p>Don't have an account? <a href="/stoneV2/view/signup.php">Sign up</a></p>
    <!-- <p><button type="submit" name="s">Login</button></p> -->
    <!-- <p> <a href="view/signup.php">Sign up</a></p> -->
    <!-- <form method="post">
        <p>Don't have an account? <button type="submit" name="signup">Sign Up</button></p>
    </form> -->
</body>
</html>
