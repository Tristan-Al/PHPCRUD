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
                            <span class="text-muted">Education level  (0-9)</span>
                            <input type="number" name="edulevel" value="' . $row['edulevel'] . '" disabled>
                            <span class="text-muted">Year</span> 
                            <input type="number" name="year" value="' . $row['year'] . '" disabled>
                            <span class="text-muted">English Level</span>
                            <input type="number" name="langlevel" value="' . $row['status'] . '" disabled>  
                        </div>
                </form>
            </div>
        </div>
    </div>
</div>
    ';

    }else {
        header("Location: index.php"); 
    }

?>

