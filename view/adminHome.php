<?php
//problem
// if(!isset($_SESSION)) session_start();

// @session_start();
// if (session_status() == PHP_SESSION_NONE) {session_start();}
// echo var_dump(session_status());
// die;
// @session_start();

// echo var_dump($_SESSION);
// die;

//Type1
// if(session_status() !== PHP_SESSION_ACTIVE){
//     // echo "<script> location.href='/stoneV2/view/login.php'; </script>";
//         header('Location: /stoneV2/view/login.php');
// }
// else {
//     // @session_start(); 
//     if(isset($_SESSION['admin']) && $_SESSION['admin']===0){
//         // echo "<script> location.href='/stoneV2/view/home.php'; </script>";
//         header('Location: /stoneV2/view/home.php');
//     }
// }


//Alternate Solution
@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
    header('Location: /stoneV2/view/login.php');
}
else{
    if(isset($_SESSION['admin']) && $_SESSION['admin']===0){
        header('Location: /stoneV2/view/home.php');
    }
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
 
    <h3>SignUp</h3>
    <?php
        if(isset($data['UsernameFormat']))  echo $data['UsernameFormat'];
        if(isset($data['PasswordFormat']))  echo $data['PasswordFormat'];
        if(isset($data['duplicateUser']))  echo $data['duplicateUser'];
        if(isset($data['signupStatus']))  echo $data['signupStatus'];
    ?>
    <form action="/stoneV2/controller/adminController.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <select name="UserType" id="UserType">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>
        <button type="submit" name="signup">Signup</button>
    </form>

    <form action="/stoneV2/controller/authController.php" method="POST">
        <button type="submit" name="logout">LogOut</button>
    </form>

    
</body>
</html>
