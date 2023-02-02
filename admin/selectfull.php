<?php
    //Chequear si tenemos una respuesta AJAX ( user_id )
    
    if ( isset( $_POST['user_id'] ) ) {
        
        $output = '<div class="text-center">
                    <i class="fa fa-triangle-exclamation modalnaranja"></i>
                    <p class="modal-title"> You are about to admit this member: </p>
                   </div>';
        
        require_once('config.php');
        require_once('DBConnection.php');

        $dbh = new DBConnection( $dbsettings );
        $conn = $dbh->getCon();
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE `user_id` = :user_id");
        $stmt->bindValue(':user_id', $_POST['user_id']);
        $success = $stmt->execute();
        
        if ( $success) {
            
            $output .= '<div class="linedetails">';
            $row = $stmt->fetch();
            $output .= '
                <div class="duser" id="myuser">'.$row['user_id'].'</div>
                <div class="dnick"><span class="dtailitem">'.$row['user_username'].'</span></div>
                <div class="dname">
                    <span>Name:</span>
                    <span class"detailitem">'.$row['first_name'] .' '.$row['last_name'].'</span>
                <div>';
                echo $output;
        }
    }


?>