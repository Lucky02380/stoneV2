<?php
session_start();

if(!isset($_SESSION["username"])){
    header('Location: /stoneV2/view/login.php');
}
else header('Location: /stoneV2/view/home.php');

if(!isset($_SESSION["admin"])){
    header('Location: /stoneV2/view/adminLogin.php');
}
else header('Location: /stoneV2/view/adminHome.php');

?>

