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

    if (isset($_SESSION['user_name'])) {
        $dbc = new DBConnection($dbsettings);
        //Get the user fields from database
        $sql = "SELECT * FROM `users` where `user_username` = '" . $_SESSION['user_name'] ."'";
        $stmt = $dbc->getQuery( $sql );          
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo '<div class="saludo">
        
        <span> Hello!</span>
        
        <div class="picture" style="background-image:url('.$_SESSION['user_pic'].')">
        
        </div> 
        
        <span class="coral">'.$row['user_username'].'</span>
    
    </div>';
    
    require_once('nav.php');

    echo '<div class="container"> 
        <div class="row"> 
            <div class="col-md-6"> 
                <div class="card"> 
                    <form action="' . $_SERVER['PHP_SELF'] . '" method="POST" class="box">
                        <div class="form-group">
                            <input type="text" name="user_username" value="' . $row['user_username'] . '" disabled> 
                            <input type="text" name="user_pass" value="' . $row['user_pass'] . '" disabled> 
                            <input type="text" name="first_name" value="' . $row['first_name'] . '" disabled> 
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" value="' . $row['last_name'] . '" disabled>
                            <input type="text" name="email" value="' . $row['email'] . '" disabled> 
                            <input type="text" name="address" value="' . $row['address'] . '" disabled> 
                        </div>
                        <div class="form-group">
                            <input type="text" name="postcode" value="' . $row['postcode'] . '" disabled>
                            <input type="text" name="city" value="' . $row['city'] . '" disabled> 
                            <input type="text" name="degree" value="' . $row['degree'] . '" disabled>  
                        </div>
                        <div class="form-group">
                            <span class="text-muted">Education level:</span>
                        </div>
                        <div class="form-group radio-group">
                            
                                <input type="radio" id="primary" name="edulevel" value="0"';
                                if($row['edulevel'] == 0) echo "checked " ;
                                echo '>
                                    <label class="text-muted" for="primary">Primary</label>

                                <input type="radio" id="secondary" name="edulevel" value="1"';
                                if($row['edulevel'] == 1) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="secondary">Secondary</label>

                                <input type="radio" id="baccalaureate" name="edulevel" value="2"';
                                if($row['edulevel'] == 2) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="baccalaureate">Baccalaureate</label>

                                <input type="radio" id="higher" name="edulevel" value="3"';
                                if($row['edulevel'] == 3) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="higher">Higher Vocational Training</label>

                                <input type="radio" id="degree" name="edulevel" value="4"';
                                if($row['edulevel'] == 4) echo "checked" ;
                            echo '>
                            <label class="text-muted" for="degree">Degree</label>
                        </div>
                        <div class="form-group">    
                            <span class="text-muted">Year</span> 
                            <input type="number" name="year" value="' . $row['year'] . '" disabled>  
                        </div>
                        <div class="form-group">
                            <span class="text-muted">English Level</span>
                        </div>
                        <div class="form-group radio-group">
                            
                                <input type="radio" id="Low" name="langlevel" value="0" ';
                                if($row['langlevel'] == 0) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="primary">Low</label>

                                <input type="radio" id="Medium" name="langlevel" value="1" ';
                                if($row['langlevel'] == 1) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="secondary">Medium</label>

                                <input type="radio" id="Hight" name="langlevel" value="2"';
                                if($row['langlevel'] == 2) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="baccalaureate">Hight</label>

                                <input type="radio" id="Excelent" name="langlevel" value="3"';
                                if($row['langlevel'] == 3) echo "checked" ;
                            echo '>
                                    <label class="text-muted" for="higher">Excelent</label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>';

    }else {
        header("Location: index.php"); 
    }

?>

