<?php
    //Script to activate php warnings
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once('header.php');
    require_once('admin/config.php');
    require_once('admin/DBConnection.php');
    require_once('admin/func.php');
    session_start();

    mt_srand( time() ); //put the random seed 
    $picuser = "ProfileIMGS/profile".mt_rand(1,23).".jpg";

    if (isset($_SESSION['user_name'])) {
        echo '<div class="saludo">
                <span> Hello!</span>    
                <div class="picture" style="background-image:url('.$picuser.')"> 

                </div> 
                <span class="coral">'.$_SESSION['user_name']."</span>
            </div>";
        require_once('usernav.php');
    }else {
        echo '<a class="btLogin" href="login.php">Login</a>';
    }
?>