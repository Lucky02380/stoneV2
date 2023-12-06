<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: /stoneV2/view/adminLogin.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>
<body>
    <h2>Admin Panel</h2>
    

    <h3>Signup User</h3>
    <?php 
        if(isset($data['usernameFormat']))  echo $data['usernameFormat'];
        if(isset($data['passwordFormat']))  echo $data['passwordFormat'];
        if(isset($data['duplicateUser']))  echo $data['duplicateUser'];
        if(isset($data['signupStatus']))  echo $data['signupStatus'];

    ?>
    <form action="/stoneV2/controller/adminController.php" method="POST">
        <label>Username: <input type="text" name="signUpusername" required></label><br>
        <label>Password: <input type="password" name="signUppassword" required></label><br>
        <button type="submit" name="signUpuser">Signup User</button>
    </form>


    <h3>Signup Admin</h3>
    <?php
        if(isset($data['AdminnameFormat']))  echo $data['AdminnameFormat'];
        if(isset($data['AdminPassFormat']))  echo $data['AdminPassFormat'];
        if(isset($data['duplicateAdmin']))  echo $data['duplicateAdmin'];
        if(isset($data['signupStatusAdmin']))  echo $data['signupStatusAdmin'];
    ?>
    <form action="/stoneV2/controller/adminController.php" method="POST">
        <label>Username: <input type="text" name="signUpAdminUsername" required></label><br>
        <label>Password: <input type="password" name="signUpAdminPassword" required></label><br>
        <button type="submit" name="signUpAdmin">Signup Admin</button>
    </form>

    <form action="/stoneV2/controller/authController.php" method="POST">
        <button type="submit" name="logout">LogOut</button>
    </form>

    
</body>
</html>
