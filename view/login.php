<?php
// echo var_dump(session_status());
// die;

//Type 1
// if(session_status() === PHP_SESSION_ACTIVE){
    
//     // echo var_dump($_SESSION);
//     // die;
//     if(isset($_SESSION['admin']) && $_SESSION['admin'] === 1){
//         // echo $_SESSION['admin'];
//         // die;

//         // echo "<script> location.href='/stoneV2/view/adminHome.php'; </script>";
//         header('Location: /stoneV2/view/adminHome.php');

//     }
//     else{
//         // echo "<script> location.href='/stoneV2/view/home.php'; </script>";
//         header('Location: /stoneV2/view/home.php');

//     }
// }


//Alternate Solution
@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
}
else if(isset($_SESSION['admin'])){
    if($_SESSION['admin']===1){
        header('Location: /stoneV2/view/adminHome.php');
    }
    else{
        header('Location: /stoneV2/view/home.php');
    }
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
    ?>
    <form action="/stoneV2/controller/authController.php" method="POST">
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <button type="submit" name="login">Login</button>
    </form>
    

</body>
</html>
