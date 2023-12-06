<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: /stoneV2/view/adminHome.php');
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
        
        if (isset($data['InvalidAdmin'])) echo $data['InvalidAdmin']; 
        if (isset($data['loginStatus'])) echo $data['loginStatus'];
        // echo "trace1";
    ?>
    <form action="/stoneV2/controller/adminController.php" method="POST">
        <label>Admin Username: <input type="text" name="AdminUsername" required></label><br>
        <label>Admin Password: <input type="password" name="AdminPassword" required></label><br>
        <button type="submit" name="login">Login</button>
    </form>
    
</body>
</html>
