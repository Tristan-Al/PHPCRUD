<?php
    require_once('admin/func.php');
    //Erase session
    unset($_SESSION);
    session_destroy();
    session_unset();
    //Delete cookies
    func::deleteCookies();
    //Go to index
    $host = $_SERVER['HTTP_HOST'];
    $route = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
    $html = 'index.php';
    $url ="http://$host$route/$html";
    header("Location: $url");
?>