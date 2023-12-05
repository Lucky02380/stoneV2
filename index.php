<?php
session_start();

if(!isset($_SESSION["username"])){
    header('Location: /stoneV2/view/login.php');
}
else header('Location: /stoneV2/view/home.php');

// $request = $_SERVER['REQUEST_URI'];
// echo $request;


// switch ($request) {
//     case '/stoneV2/':
//         // echo "A:LDF1";
//         require 'view/signup.php';
//         break;

//     case '/stoneV2/view/signup':
//         echo "A:LDF2";
//         require 'controller/authController.php';
//         break;

//     case '/stoneV2/view/login':
//         // echo "A:LDF3";
//         require 'view/signup.php';
//         break;
    
//     default:
//         // echo "A:LDF";
//         require 'view/login.php';
        
// }



?>

