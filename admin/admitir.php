<?php

    require_once('config.php');
    require_once('DBConnection.php');
    require_once('func.php');

    $dbh = new DBConnection( $dbsettings );
    $conn = $dbh->getCon();

    //Si estoy logeado
    if ( func::checkLogin( $conn )) {
        //Si soy el admin
        if ( $_SESSION['user_id'] == 1 ) {
            if ( isset( $_GET['user_id'] ) ) {
                $user_id = $_GET['user_id'];
                $query = " UPDATE `users` SET `status` = '1' WHERE `user_id` = :user_id";
                $stmt = $conn->prepare( $query );
                $stmt->bindValue( ':user_id', $user_id );
                $success = $stmt->execute();
                header('Location: index.php');
            }
        }else {
            header('Location: /index/php?error=5');
        }
    }else {
        header('Location: /index/php?error=5');
    }

