<?php
session_start();
if (isset($_SESSION['username'])) {
    header('Location: /stoneV2/view/home.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
</head>
<body>
    <h2>User Login</h2>
    <?php 
        if(isset($data['usernameFormat']))  echo $data['usernameFormat'];
        if(isset($data['passwordFormat']))  echo $data['passwordFormat'];
        if (isset($data['InvalidUsername'])) echo $data['InvalidUsername']; 
        if (isset($data['loginStatus'])) echo $data['loginStatus'];
    ?>
    <form action="/stoneV2/controller/authController.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" name="login">Login</button>
    </form>
    
    <p><a href="/stoneV2/view/adminLogin.php">Admin Login</a></p>

</body>
</html>
