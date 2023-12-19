<?php
//Type1
// if(session_status() !== PHP_SESSION_ACTIVE){
//     header('Location: /stoneV2/view/login.php');
// }
// else{
//     if($_SESSION['admin'] === 0) header('Location: /stoneV2/view/home.php');
//     else if($_SESSION['admin'] === 1) header('Location: /stoneV2/view/adminHome.php');
// }

@session_start();
if(!isset($_SESSION['admin'])){
    session_destroy();
    header('Location: /stoneV2/view/login.php');
}
else{
    if($_SESSION['admin']===1){
        header('Location: /stoneV2/view/adminHome.php');
    }
    else{
        header('Location: /stoneV2/view/home.php');
    }
}




?>

