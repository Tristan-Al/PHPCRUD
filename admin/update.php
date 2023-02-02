<?php
    //Chequear si tenemos una respuesta AJAX ( user_id )
    
    if ( isset( $_POST['user_id'] ) ) {
        
        $output = '<div class="text-center">
                    <i class="fa fa-triangle-exclamation modalnaranja"></i>
                    <p class="modal-title"> Edit member </p>
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

                <select name="edulevel" id="type" class="">
                        <option value="0" <?php if($type == "Choose a type") echo "selected"; ?> > Choose a type </option>
                        <option value="1" <?php if($type == "Running") echo "selected"; ?> > Running </option>
                        <option value="2" <?php if($type == "Swimming") echo "selected"; ?> > Swimming </option>
                        <option value="3" <?php if($type == "Cycling") echo "selected"; ?> > Cycling </option>
                        <option value="4" <?php if($type == "Triathlon") echo "selected"; ?> > Triathlon </option>
                    </select>
                    
                echo $output;
        }
    }


?>