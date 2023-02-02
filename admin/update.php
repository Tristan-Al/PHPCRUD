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
                <form action="' . $_SERVER['PHP_SELF'] . '" method="POST" class="">
                    <div class="">
                        <input type="text" name="user_username" value="' . $row['user_username'] . '"> 
                        <input type="password" name="user_pass" value="' . $row['user_pass'] . '"> 
                        <input type="text" name="first_name" value="' . $row['first_name'] . '"> 
                    </div>
                    <br>
                    <div class="">
                        <input type="text" name="last_name" value="' . $row['last_name'] . '">
                        <input type="text" name="email" value="' . $row['email'] . '"> 
                        <input type="text" name="address" value="' . $row['address'] . '"> 
                    </div>
                    <br>
                    <div class="">
                        <input type="text" name="postcode" value="' . $row['postcode'] . '">
                        <input type="text" name="city" value="' . $row['city'] . '"> 
                        <input type="text" name="degree" value="' . $row['degree'] . '">  
                    </div>
                    <br>
                    <div class="">
                        <span class="text-muted">Education level:</span>
                    </div>
                    <br>
                    <div class="">
                        hol
                            <input type="radio" id="primary" name="edulevel" value="0" ';

                            if($row['edulevel'] == 0) $output.= "checked " ;
                            
                            $output.= '>
                                <label class="text-muted" for="primary">Primary</label>

                            <input type="radio" id="secondary" name="edulevel" value="1"';
                            if($row['edulevel'] == 1) $output.= "checked" ;
                            $output.=  '>
                                <label class="text-muted" for="secondary">Secondary</label>

                            <input type="radio" id="baccalaureate" name="edulevel" value="2"';
                            if($row['edulevel'] == 2) $output.= "checked" ;
                            $output.=  '>
                                <label class="text-muted" for="baccalaureate">Baccalaureate</label>

                            <input type="radio" id="higher" name="edulevel" value="3"';
                            if($row['edulevel'] == 3) $output.= "checked" ;
                            $output.= '>
                                <label class="text-muted" for="higher">Higher Vocational Training</label>

                            <input type="radio" id="degree" name="edulevel" value="4"';
                            if($row['edulevel'] == 4) $output.= "checked" ;
                            $output.= '>
                        <label class="text-muted" for="degree">Degree</label>
                    </div>
                    <br>
                    <div class="">    
                        <span class="text-muted">Year</span> 
                        <input type="number" name="year" value="' . $row['year'] . '">  
                    </div>
                    <br>
                    <div class="">
                        <span class="text-muted">English Level</span>
                    </div>
                    <br>
                    <div class="">
                        
                            <input type="radio" id="Low" name="langlevel" value="0" ';
                            if($row['langlevel'] == 0) $output.= "checked" ;
                            $output.= '>
                                <label class="text-muted" for="primary">Low</label>

                            <input type="radio" id="Medium" name="langlevel" value="1" ';
                            if($row['langlevel'] == 1) $output.= "checked" ;
                            $output.= '>
                                <label class="text-muted" for="secondary">Medium</label>

                            <input type="radio" id="Hight" name="langlevel" value="2"';
                            if($row['langlevel'] == 2) $output.= "checked" ;
                            $output.= '>
                                <label class="text-muted" for="baccalaureate">Hight</label>

                            <input type="radio" id="Excelent" name="langlevel" value="3"';
                            if($row['langlevel'] == 3) $output.= "checked" ;
                            $output.= '>
                                <label class="text-muted" for="higher">Excelent</label>
                    </div>
                </form>';
                    
                echo $output;
        }
    }


?>