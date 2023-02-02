<?php
//Script php para activar la visualizacion de warnings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('header.php');
require_once('admin/config.php');
require_once('admin/DBConnection.php');
require_once('admin/func.php');
require_once('User.php');

if (isset($_POST['submit'])) {

    //Check the required fields
    if (empty($_POST['user_username']) && empty($_POST['user_pass'])) {

        $e = func::showError(1);

    }else{
        // Harvesting
        $username = $_POST['user_username'];
        $password = sha1($_POST['user_pass']);

        //try to connect to the database
        $dbc = new DBConnection($dbsettings);
                    
        //check the connection
        if (!$dbc) {

            $e = func::showError(2);
        
        }else{

            //Check if the user and password exists in DataBase
            $sql = "SELECT * FROM users WHERE user_username='".$username."' AND user_pass='".$password."'";
            
            $resultSet = $dbc->getQuery( $sql );

            if ( $resultSet->rowCount() == 1) {

                $e = func::showError(4);

            }else{
                //pick up the current date and change his format to mysql
                $_POST['member_from'] = date('Y-m-d');
                $_POST['user_pass'] = $password;
                $_POST['trash'] = 0;
                $_POST['status'] = 0;
                $myUser = new User($_POST);
                $sql = "INSERT INTO users VALUES (0, ".implode(", ", $myUser->getFields()).")";
                $afectedRows = $dbc->runQuery($sql);
            
                if ($afectedRows != 1) {
                
                    $e = func::showError(5);
                
                }else{
                    
                    $e = func::showError(0);
                
                }

            }
        }

    }

}

?>

<div class="container"> 
    <div class="row"> 
        <div class="col-md-6"> 
            <div class="card"> 
            <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" class="box"> 
                <p class="text-muted"> <?php if(isset( $e )){ echo $e;} ?> </p>
                <h1>Sign Up</h1> 
                <input type="text" name="user_username" placeholder="Username"> 
                <input type="password" name="user_pass" placeholder="Password">
                <input type="text" name="first_name" placeholder="First Name"> 
                <input type="text" name="last_name" placeholder="Last Name"> 
                <input type="text" name="email" placeholder="Email"> 
                <input type="text" name="address" placeholder="Address"> 
                <input type="text" name="postcode" placeholder="Post code"> 
                <input type="text" name="city" placeholder="City"> 
                <span class="text-muted">Education level</span> <br><br>
                <input type="radio" id="primary" name="edulevel" value="0" checked>
                    <label class="text-muted" for="primary">Primary</label>

                <input type="radio" id="secondary" name="edulevel" value="1">
                    <label class="text-muted" for="secondary">Secondary</label>

                <input type="radio" id="baccalaureate" name="edulevel" value="2">
                    <label class="text-muted" for="baccalaureate">Baccalaureate</label>

                <input type="radio" id="higher" name="edulevel" value="3">
                    <label class="text-muted" for="higher">Higher Vocational Training</label>

                <input type="radio" id="degree" name="edulevel" value="4">
                    <label class="text-muted" for="degree">Degree</label>

                <input type="text" name="degree" placeholder="Degree">

                <span class="text-muted">Year</span>
                <input type="number" name="year" min="1902" max="2022"> <br><br>              
                <span class="text-muted">Lang Level</span>
                
                <input type="radio" id="Low" name="langlevel" value="0" checked>
                    <label class="text-muted" for="primary">Low</label>

                <input type="radio" id="Medium" name="langlevel" value="1">
                    <label class="text-muted" for="secondary">Medium</label>

                <input type="radio" id="Hight" name="langlevel" value="2">
                    <label class="text-muted" for="baccalaureate">Hight</label>

                <input type="radio" id="Excelent" name="langlevel" value="3">
                    <label class="text-muted" for="higher">Excelent</label>

                <input type="submit" name="submit"> 
                
                <a class="forgot" href="login.php">Sing in</a>      
                </div> 
            </form> 
             
        </div> 
    </div> 
</div>
</div>


<?php
        require_once('foother.php');
?>