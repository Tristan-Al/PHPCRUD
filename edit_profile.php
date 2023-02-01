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

        if (isset($_POST['submit'])) {

            //Check the required fields
            if (empty($_POST['user_username']) && empty($_POST['user_pass'])) {
        
                $e = func::showError(1);
        
            }else{
                
                    $sql = "UPDATE `users` SET
                            `user_username`='" . $_POST['user_username'] . "'
                            ,`user_pass`='" . $_POST['user_pass'] . "'
                            ,`first_name`='" . $_POST['first_name'] . "'
                            ,`last_name`='" . $_POST['last_name'] . "'
                            ,`email`='" . $_POST['email'] . "'
                            ,`address`='" . $_POST['address'] . "'
                            ,`postcode`='" . $_POST['postcode'] . "'
                            ,`city`='" . $_POST['city'] . "'
                            ,`edulevel`='" . $_POST['edulevel'] . "'
                            ,`degree`='" . $_POST['degree'] . "'
                            ,`year`='" . $_POST['year'] . "'
                            ,`langlevel`='" . $_POST['langlevel'] . "'
                            ,`status`='" . $row['status'] . "'
                            ,`trash`='" . $row['trash'] . "'
                            ,`member_from`='" . $row['member_from'] . "' WHERE user_id = " . $row['user_id'] ." ";
                    $afectedRows = $dbc->runQuery($sql);
                
                    if ($afectedRows != 1) {
                    
                        $e = func::showError(5);
                    
                    }else{
                        
                        $e = func::showError(0);
                    
                    }
                
            }
        }

        echo '<div class="saludo">
        
        <span> Hello!</span>
        
        <div class="picture" style="background-image:url(' . $_SESSION['user_pic'] . ')">
        
        </div> 
        
        <span class="coral">' . $row['user_username'] . '</span>
    
        </div>';
        
        require_once('nav.php');

        echo '<div class="container"> 
        <div class="row"> 
            <div class="col-md-6"> 
                <div class="card"> 
                    <form action="' . $_SERVER['PHP_SELF'] . '" method="POST" class="box">
                    <div class="form-group">
                    <p class="text-muted"> '; if(isset( $e )){ echo $e;} echo ' </p> 
                    </div>
                        <div class="form-group">
                            <input type="text" name="user_username" value="' . $row['user_username'] . '"> 
                            <input type="text" name="user_pass" value="' . $row['user_pass'] . '"> 
                            <input type="text" name="first_name" value="' . $row['first_name'] . '"> 
                        </div>
                        <div class="form-group">
                            <input type="text" name="last_name" value="' . $row['last_name'] . '">
                            <input type="text" name="email" value="' . $row['email'] . '"> 
                            <input type="text" name="address" value="' . $row['address'] . '"> 
                        </div>
                        <div class="form-group">
                            <input type="text" name="postcode" value="' . $row['postcode'] . '">
                            <input type="text" name="city" value="' . $row['city'] . '"> 
                            <input type="text" name="degree" value="' . $row['degree'] . '">  
                        </div>
                        <div class="form-group">
                            <span class="text-muted">Education level  (0-9)</span>
                            <input type="number" name="edulevel" value="' . $row['edulevel'] . '">
                            <span class="text-muted">Year</span> 
                            <input type="number" name="year" value="' . $row['year'] . '">
                            <span class="text-muted">English Level</span>
                            <input type="number" name="langlevel" value="' . $row['status'] . '">  
                        </div>
                        <div class="form-group">
                            <input type="submit" name="submit"> 
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